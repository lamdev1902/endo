<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Controllers;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\ColumnFilterForm;
use GeminiLabs\SiteReviews\Addon\Forms\Defaults\FieldDefaults;
use GeminiLabs\SiteReviews\Addon\Forms\Defaults\FieldTypeSanitizerDefaults;
use GeminiLabs\SiteReviews\Addon\Forms\FieldElements\AssignedPosts;
use GeminiLabs\SiteReviews\Addon\Forms\FieldElements\AssignedTerms;
use GeminiLabs\SiteReviews\Addon\Forms\FieldElements\AssignedUsers;
use GeminiLabs\SiteReviews\Addon\Forms\FormFields;
use GeminiLabs\SiteReviews\Addon\Forms\ReviewTemplate;
use GeminiLabs\SiteReviews\Contracts\DefaultsContract;
use GeminiLabs\SiteReviews\Contracts\FieldContract;
use GeminiLabs\SiteReviews\Contracts\FormContract;
use GeminiLabs\SiteReviews\Defaults\CustomFieldsDefaults;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\HookProxy;
use GeminiLabs\SiteReviews\Modules\Html\Field;
use GeminiLabs\SiteReviews\Modules\Html\FieldCondition;
use GeminiLabs\SiteReviews\Modules\Html\ReviewForm;
use GeminiLabs\SiteReviews\Modules\Html\ReviewHtml;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;

class FieldController
{
    use HookProxy;

    /**
     * @filter site-reviews/enqueue/admin/localize
     */
    public function filterAdminLocalizedVariables(array $variables): array
    {
        $variables['addons'][Application::ID] = [
            'criteria' => [
                'conditions' => [
                    'always' => _x('Always', 'admin-text', 'site-reviews-forms'),
                    'all' => _x('When all of the conditions pass', 'admin-text', 'site-reviews-forms'),
                    'any' => _x('When any of the conditions pass', 'admin-text', 'site-reviews-forms'),
                ],
                'operators' => [
                    'contains' => _x('Contains', 'admin-text', 'site-reviews-forms'),
                    'equals' => _x('Equals', 'admin-text', 'site-reviews-forms'),
                    'greater' => _x('Greater Than', 'admin-text', 'site-reviews-forms'),
                    'less' => _x('Less Than', 'admin-text', 'site-reviews-forms'),
                    'not' => _x('Not', 'admin-text', 'site-reviews-forms'),
                ],
                'restrictions' => $this->getFieldRestrictions(),
            ],
            'defaults' => $this->getFieldDefaults(),
            'formats' => $this->getFieldFormats(),
            'labels' => [
                'conditions' => _x('Display Field', 'admin-text', 'site-reviews-forms'),
                'description' => _x('Field Description', 'admin-text', 'site-reviews-forms'),
                'format' => _x('Display Value As', 'admin-text', 'site-reviews-forms'),
                'label' => _x('Field Label', 'admin-text', 'site-reviews-forms'),
                'labels' => _x('Range Labels', 'admin-text', 'site-reviews-forms'),
                'name' => _x('Field Name', 'admin-text', 'site-reviews-forms'),
                'options' => _x('Field Options', 'admin-text', 'site-reviews-forms'),
                'placeholder' => _x('Placeholder', 'admin-text', 'site-reviews-forms'),
                'posttypes' => _x('Post Type', 'admin-text', 'site-reviews-forms'),
                'required' => _x('Required', 'admin-text', 'site-reviews-forms'),
                'responsive_width' => _x('Field Width', 'admin-text', 'site-reviews-forms'),
                'roles' => _x('User Role', 'admin-text', 'site-reviews-forms'),
                'tag' => _x('Template Tag', 'admin-text', 'site-reviews-forms'),
                'tag_label' => _x('Template Tag Label', 'admin-text', 'site-reviews-forms'),
                'terms' => _x('Category', 'admin-text', 'site-reviews-forms'),
                'type' => _x('Field Type', 'admin-text', 'site-reviews-forms'),
                'users' => _x('User', 'admin-text', 'site-reviews-forms'),
                'value' => _x('Default Value', 'admin-text', 'site-reviews-forms'),
            ],
            'messages' => [
                'between' => _x('The %s must be between %d and %d', 'admin-text', 'site-reviews-forms'),
                'criteria' => _x('The %s requires at least one condition', 'admin-text', 'site-reviews-forms'),
                'number' => _x('The %s must be a number', 'admin-text', 'site-reviews-forms'),
                'required' => _x('The %s is required', 'admin-text', 'site-reviews-forms'),
                'reserved' => _x('The "%s" value is reserved', 'admin-text', 'site-reviews-forms'),
                'slug' => _x('The %s must be an alphabetic (a-z) lowercase word with no spaces. Underscores are allowed.', 'admin-text', 'site-reviews-forms'),
                'unique' => _x('The %s must be unique', 'admin-text', 'site-reviews-forms'),
            ],
            'options' => $this->getFieldOptions(),
            'reserved_names' => $this->getReservedNames(),
            'reserved_tags' => glsr(ReviewTemplate::class)->reservedTags(),
            'validation' => $this->getFieldValidation(),
        ];
        $variables['filters']['form'] = glsr(ColumnFilterForm::class)->options();
        $variables['nonce']['filter-form'] = wp_create_nonce('filter-form');
        $variables['nonce']['metabox-details'] = wp_create_nonce('metabox-details');
        return $variables;
    }

    /**
     * @filter site-reviews/rendered/field/classes
     */
    public function filterFieldClasses(array $classes, FieldContract $field): array
    {
        if (true !== $field->is_custom) {
            return $classes;
        }
        $args = glsr(FieldDefaults::class)->merge($field->toArray());
        foreach ($args['responsive_width'] as $size => $class) {
            if (empty($class)) {
                continue;
            }
            if ('sm' === $size) {
                $classes[] = $class;
                continue;
            }
            $classes[] = "{$size}\:{$class}";
        }
        return $classes;
    }

    /**
     * @filter site-reviews/field/element/assigned_posts
     */
    public function filterFieldElementAssignedPosts(): string
    {
        return AssignedPosts::class;
    }

    /**
     * @filter site-reviews/field/element/assigned_terms
     */
    public function filterFieldElementAssignedTerms(): string
    {
        return AssignedTerms::class;
    }

    /**
     * @filter site-reviews/field/element/assigned_users
     */
    public function filterFieldElementAssignedUsers(): string
    {
        return AssignedUsers::class;
    }

    /**
     * @param FieldContract[] $fields
     *
     * @filter site-reviews/review-form/fields/all
     */
    public function filterHiddenAssignmentFieldValues(array $fields, FormContract $form): array
    {
        $assignmentFieldKeys = ['assigned_posts', 'assigned_terms', 'assigned_users'];
        array_walk($fields, function ($field) use ($assignmentFieldKeys, $form) {
            if (!in_array($field->original_name, $assignmentFieldKeys)) {
                return;
            }
            if ('hidden' !== $field->type) {
                return;
            }
            if (empty($field->handle)) { // only custom assignment fields will have a handle
                return;
            }
            $values = Arr::uniqueInt(array_merge(
                explode(',', $field->value),
                explode(',', $form->args()->get($field->original_name)),
            ));
            $field->value = implode(',', $values);
        });
        return $fields;
    }

    /**
     * @filter site-reviews/review-form/fields/hidden
     */
    public function filterHiddenFields(array $fields, FormContract $form): array
    {
        $assignmentFieldKeys = ['assigned_posts', 'assigned_terms', 'assigned_users'];
        $config = $form->config();
        foreach ($config as $args) {
            $name = $args['name'] ?? '';
            if (in_array($name, $assignmentFieldKeys)) {
                unset($fields[$name]);
            }
        }
        if (!empty($form->args()->form)) {
            $fields['form'] = new Field([
                'name' => 'form',
                'type' => 'hidden',
                'value' => $form->args()->form,
            ]);
        }
        return $fields;
    }

    /**
     * @filter site-reviews/metabox-form/fields
     */
    public function filterMetaboxFieldsConfig(array $config, FormContract $form): array
    {
        return glsr(FormFields::class)->metaboxConfig(
            $form->review->custom()->cast('form', 'int'),
            $config,
        );
    }

    /**
     * @param FieldContract[] $fields
     *
     * @filter site-reviews/review-form/fields/all
     */
    public function filterMultiFields(array $fields, FormContract $form): array
    {
        $customFields = glsr(FormFields::class)->indexedFields($form->args()->cast('form', 'int'));
        $multiFieldKeys = ['assigned_posts', 'assigned_terms', 'assigned_users'];
        $names = array_count_values(wp_list_pluck($customFields, 'name'));
        foreach ($multiFieldKeys as $key) {
            if (Cast::toInt(Arr::get($names, $key)) < 2) {
                continue;
            }
            array_walk($fields, function ($field) use ($key) {
                if ($field->original_name === $key) {
                    $field->name = Str::suffix($field->name, '[]');
                }
            });
        }
        return $fields;
    }

    /**
     * Set the default values for custom fields in the review.
     *
     * @action site-reviews/review/build/before
     */
    public function filterReviewCustomDefaults(Review $review, ReviewHtml $reviewHtml): void
    {
        $formId = Arr::getAs('int', $reviewHtml, 'args.form');
        $fields = glsr(FormFields::class)->customFields($formId);
        if (!empty($fields)) {
            array_walk($fields, function (&$field) {
                $field = Arr::get($field, 'value');
            });
            $custom = wp_parse_args($review->custom->toArray(), $fields);
            $review->set('custom', glsr()->args($custom));
        }
    }

    /**
     * Default fields are keyed, custom fields are indexed to allow multiple assigned_posts fields.
     *
     * @filter site-reviews/review-form/fields
     */
    public function filterReviewFormConfig(array $config, FormContract $form): array
    {
        $indexedCustomFields = glsr(FormFields::class)->normalizedFieldsIndexed(
            $form->args()->cast('form', 'int')
        );
        if (empty($indexedCustomFields)) {
            return $config;
        }
        foreach ($indexedCustomFields as &$field) {
            $name = Arr::get($field, 'name');
            $type = Arr::get($field, 'type');
            if (str_starts_with($type, 'review_')) {
                $field['type'] = Str::removePrefix($type, 'review_');
            }
            if ('images' === $name) {
                glsr()->store('use_dropzone', true); // override the images hide option
            }
            if ('hidden' === $type) {
                $field['is_raw'] = true; // do not wrap hidden fields
            }
            if ('textarea' === $type) {
                $field['rows'] = 5;
            }
            if (empty($field['validation'])) {
                $validation = [];
                if (0 < ($field['minlength'] ?? 0)) {
                    $validation[] = "min:{$field['minlength']}";
                }
                if (0 < ($field['maxlength'] ?? 0)) {
                    $validation[] = "max:{$field['maxlength']}";
                }
                $field['validation'] = implode('|', $validation);
            }
        }
        return $indexedCustomFields;
    }

    /**
     * @filter site-reviews/shortcode/args
     */
    public function filterShortcodeAttributes(array $attributes, string $shortcode): array
    {
        if ('site_reviews_form' !== $shortcode) {
            return $attributes;
        }
        $fields = glsr(FormFields::class)->normalizedFieldsIndexed(
            glsr()->args($attributes)->cast('form', 'int')
        );
        if (!empty($fields)) {
            $attributes['class'] = ($attributes['class'] ?? '').' glsr-form-responsive'; // enable responsive field widths
            $attributes['hide'] = ''; // use the custom form configuration instead of the hide option
        }
        return $attributes;
    }

    /**
     * @filter site-reviews/validation/rules
     */
    public function filterValidationRules(array $rules, Request $request): array
    {
        $formId = $request->cast('form', 'int');
        if (Application::POST_TYPE !== get_post_type($formId)) {
            return $rules;
        }
        $form = new ReviewForm(['form' => $formId], $request->toArray());
        $fields = array_filter($form->visible(), fn ($field) => !$field->is_hidden);
        $rules = array_filter(wp_list_pluck($fields, 'validation', 'original_name'));
        return $rules;
    }

    /**
     * @action admin_footer
     */
    public function renderFieldTemplates(): void
    {
        global $hook_suffix;
        if (!in_array($hook_suffix, ['post.php', 'post-new.php'])) {
            return;
        }
        if (Application::POST_TYPE !== get_post_type()) {
            return;
        }
        glsr(Application::class)->render('views/templates', [
            'customFields' => $this->getFields(['Custom']),
            'reviewFields' => $this->getFields(['Review']),
        ]);
    }

    /**
     * @action site-reviews/defaults
     */
    public function setFieldSanitizers(DefaultsContract $defaults, string $hook, string $method, array $values): void
    {
        if ('custom-fields' !== $hook) {
            return;
        }
        if (empty($values['form'])) {
            return;
        }
        $fields = glsr(FormFields::class)->customFields((int) $values['form']);
        if (!empty($fields)) {
            $sanitizers = glsr(FieldTypeSanitizerDefaults::class)->defaults();
            $sanitize = array_map(function ($field) use ($sanitizers) {
                return Arr::get($sanitizers, Arr::get($field, 'type'), 'text');
            }, $fields);
            $defaults->sanitize = wp_parse_args($sanitize, $defaults->sanitize);
        }
    }

    protected function getFieldDefaults(): array
    {
        $fields = $this->getFields();
        $defaults = [];
        foreach ($fields as $field) {
            $defaults[] = $field->defaults;
        }
        return $defaults;
    }

    protected function getFieldFormats(): array
    {
        $fields = $this->getFields();
        $formats = [];
        foreach ($fields as $field) {
            $formats[$field->type] = $field->formats();
        }
        return $formats;
    }

    protected function getFieldOptions(): array
    {
        $fields = $this->getFields();
        $options = [];
        foreach ($fields as $field) {
            $options[$field->type] = $field->options;
        }
        return $options;
    }

    protected function getFieldRestrictions(): array
    {
        $fields = $this->getFields();
        $restrictions = [];
        foreach ($fields as $field) {
            $restrictions[$field->type] = $field->criteria;
        }
        return $restrictions;
    }

    protected function getFields(array $startsWith = ['Custom', 'Review']): array
    {
        $fields = [];
        $dir = glsr(Application::class)->path('plugin/Fields');
        if (!is_dir($dir)) {
            return $fields;
        }
        $iterator = new \DirectoryIterator($dir);
        foreach ($iterator as $fileinfo) {
            $file = $fileinfo->getFilename();
            if (!$fileinfo->isFile() || !Str::startsWith($file, $startsWith)) {
                continue;
            }
            $file = str_replace('.php', '', $file);
            $field = glsr(Helper::buildClassName($file, 'Addon\Forms\Fields'));
            if ($field->isActive()) {
                $fields[$field->handle] = $field;
            }
        }
        ksort($fields);
        return $fields;
    }

    protected function getFieldValidation(): array
    {
        $fields = $this->getFields();
        $validation = [];
        foreach ($fields as $field) {
            $validation[$field->type] = $field->parsedValidation();
        }
        return $validation;
    }

    protected function getReservedNames(): array
    {
        $names = array_merge(['form'], glsr(CustomFieldsDefaults::class)->guarded);
        sort($names);
        return $names;
    }
}
