<?php

namespace GeminiLabs\SiteReviews\Addon\Forms;

use GeminiLabs\SiteReviews\Addon\Forms\Defaults\FieldDefaults;
use GeminiLabs\SiteReviews\Addon\Forms\Fields\ReviewContent;
use GeminiLabs\SiteReviews\Addon\Forms\Fields\ReviewEmail;
use GeminiLabs\SiteReviews\Addon\Forms\Fields\ReviewImages;
use GeminiLabs\SiteReviews\Addon\Forms\Fields\ReviewName;
use GeminiLabs\SiteReviews\Addon\Forms\Fields\ReviewRating;
use GeminiLabs\SiteReviews\Addon\Forms\Fields\ReviewTerms;
use GeminiLabs\SiteReviews\Addon\Forms\Fields\ReviewTitle;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Str;

class FormFields
{
    public const META_KEY = '_fields';

    public function customFields(int $formId): array
    {
        $fields = $this->normalizedFieldsKeyed($formId);
        $defaults = $this->defaultFields();
        return array_filter($fields, function ($field) use ($defaults) {
            return !in_array($field['name'], wp_list_pluck($defaults, 'name'))
                && !$this->isProtectedField($field);
        });
    }

    public function customTemplateTags(int $formId): array
    {
        $fields = $this->customFields($formId);
        array_walk($fields, function (&$field) {
            $field = Arr::get($field, 'tag');
        });
        return array_filter($fields); // field_name => tag
    }

    public function defaultFields(): array
    {
        static $fields;
        if (empty($fields)) {
            $fields = Cast::toArray(glsr()->config('forms/review-form', false)); // bypass filters
            array_walk($fields, function (&$field, $name) {
                $field['name'] = $name;
            });
        }
        return $fields;
    }

    /**
     * Indexed array.
     */
    public function defaultFieldsForMetaboxIndexed(): array
    {
        $fieldClassnames = [ // order is intentional
            ReviewRating::class,
            ReviewTitle::class,
            ReviewContent::class,
            ReviewName::class,
            ReviewEmail::class,
            ReviewImages::class,
            ReviewTerms::class,
        ];
        $fields = [];
        foreach ($fieldClassnames as $classname) {
            $fieldClass = glsr($classname);
            if ($fieldClass->isActive()) {
                $field = $fieldClass->defaults;
                $field['hidden'] = false;
                $field['required'] = true;
                $fields[] = $field;
            }
        }
        return $this->normalize($fields);
    }

    /**
     * This returns the raw meta data value.
     * Indexed array.
     */
    public function indexedFields(int $formId): array
    {
        $fields = Arr::consolidate(get_post_meta($formId, static::META_KEY, true));
        foreach ($fields as &$field) {
            $field = wp_parse_args($field, [
                'is_custom' => true, // this is a custom form field
                'conditions' => 'always',
                'validation' => '', // @todo add validation here
            ]);
        }
        return $fields;
    }

    public function metaboxConfig(int $formId, array $config): array
    {
        $config['form'] = [
            'label' => _x('Custom Form', 'admin-text', 'site-reviews-forms'),
            'type' => 'select',
            'options' => glsr(Application::class)->posts(-1, '&mdash; '._x('Default Form', 'admin-text', 'site-reviews-forms').' &mdash;'),
            'value' => $formId,
        ];
        if ($fields = $this->normalizedFieldsKeyed($formId)) {
            $fields = array_merge($fields, array_diff_key($config, $fields));
            foreach ($fields as $name => $field) {
                if ($this->isProtectedField($field) || 'form' === $name) {
                    continue;
                }
                if (empty($field['label'])) {
                    $field['label'] = $name;
                }
                if ('hidden' === $field['type']) {
                    $field['type'] = 'text';
                }
                $keys = ['label', 'options', 'type'];
                $newConfig[$name] = array_filter(shortcode_atts(array_fill_keys($keys, ''), $field));
            }
            if (isset($config['terms'])) {
                $newConfig['terms'] = $config['terms']; // support the new v5.9 "terms" db column
            }
            $newConfig['form'] = $config['form'];
            return $newConfig;
        }
        return $config;
    }

    public function normalize(array $fields): array
    {
        return array_map([glsr(FieldDefaults::class), 'merge'], $fields);
    }

    /**
     * Indexed array.
     */
    public function normalizedFieldsIndexed(int $formId): array
    {
        $fields = $this->indexedFields($formId);
        if (!empty($fields)) {
            $fields = array_map([$this, 'normalizeField'], $fields);
        }
        return $fields;
    }

    public function normalizedFieldsKeyed(int $formId): array
    {
        $fields = [];
        $indexedFields = $this->normalizedFieldsIndexed($formId);
        foreach ($indexedFields as $field) {
            $fields[Arr::get($field, 'name')] = $field;
        }
        return $fields;
    }

    /**
     * Indexed array.
     */
    public function normalizedFieldsForMetaboxIndexed(int $formId): array
    {
        $fields = $this->indexedFields($formId);
        return $this->normalize($fields);
    }

    public function saveFields(int $formId, array $fields): void
    {
        $fields = $this->normalize($fields);
        update_post_meta($formId, static::META_KEY, $fields);
    }

    protected function isProtectedField(array $field): bool
    {
        $exclude = ['content', 'images', 'terms', 'title', 'response'];
        return in_array(Arr::get($field, 'name'), $exclude)
            || str_starts_with(Arr::get($field, 'type'), 'review_');
    }

    protected function normalizeCustomField(array $field): array
    {
        $custom = glsr()->args($field);
        if ('select' === $custom->type && !empty($custom->placeholder)) {
            $field['options'] = Arr::prepend((array) $custom->options, $custom->placeholder, ''); // @phpstan-ignore-line
        }
        return glsr(FieldDefaults::class)->merge($field);
    }

    protected function normalizeField(array $field): array
    {
        $defaults = $this->defaultFields();
        if (!Cast::toInt($field['maxlength'] ?? 0)) {
            unset($field['maxlength']);
        }
        if (!Cast::toInt($field['minlength'] ?? 0)) {
            unset($field['minlength']);
        }
        $field = glsr()->args($field);
        $name = Str::removePrefix($field->type, 'review_');
        if ($default = Arr::get($defaults, $name)) {
            $default['label'] = $field->label;
            $default['name'] = $field->get('name', $name);
            $default['options'] = $field->options;
            $default['placeholder'] = $field->placeholder;
            $default['required'] = $field->cast('required', 'bool');
            $normalizedField = wp_parse_args($default, $field->toArray());
            return glsr(FieldDefaults::class)->merge($normalizedField);
        }
        return $this->normalizeCustomField($field->toArray());
    }
}
