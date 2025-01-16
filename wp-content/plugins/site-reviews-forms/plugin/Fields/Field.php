<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Helpers\Arr;

abstract class Field
{
    /**
     * The available criteria operators and values of the field.
     */
    public array $criteria;

    /**
     * The default values of the field, these are used to generate the field HTML in the form.
     */
    public array $defaults;

    /**
     * The field handle is used in the Form UI if there is no label set.
     */
    public string $handle;

    /**
     * The field [name] attribute key.
     */
    public string $name = '';

    /**
     * The field options to display for this field type.
     */
    public array $options;

    /**
     * The field tag is used in the review template to display the field value.
     */
    public string $tag = '';

    /**
     * This is the actual field type which used to determine the HTML tag of the field.
     */
    public string $type;

    /**
     * The validation rules for the field options.
     */
    public array $validation;

    public function __construct()
    {
        $fields = glsr()->config('forms/review-form', false);
        $this->criteria = [
            'operators' => array_values(array_intersect($this->conditionOperators(), [
                'contains', 'equals', 'greater', 'less', 'not'
            ])),
            'values' => $this->conditionValues(),
        ];
        $this->defaults = wp_parse_args($this->defaults(), [
            'format' => '',
            'format_link_text' => '',
            'handle' => $this->handle(),
            'label' => Arr::get($fields, $this->name.'.label'),
            'name' => $this->name,
            'placeholder' => Arr::get($fields, $this->name.'.placeholder'),
            'tag' => $this->tag,
            'tag_label' => '',
            'type' => $this->type(),
        ]);
        $this->handle = $this->handle();
        $this->options = $this->options();
        $this->type = $this->type();
        $this->validation = wp_parse_args($this->validation(), [
            'name' => 'required|reserved|slug|unique',
            'tag' => 'reserved|slug|unique',
        ]);
        glsr(Application::class)->action('field', $this);
    }

    public function formats(): array
    {
        return [];
    }

    public function isActive(): bool
    {
        return true;
    }

    public function parsedValidation(): array
    {
        $parsed = [];
        foreach ($this->parseRules() as $attribute => $rules) {
            $parsed[$attribute] = [];
            foreach ($rules as $rule) {
                list($rule, $parameters) = $this->parseRule($rule);
                $parsed[$attribute][$rule] = $parameters;
            }
        }
        return $parsed;
    }

    protected function conditionOperators(): array
    {
        return [];
    }

    protected function conditionValues(): array
    {
        return [];
    }

    protected function defaults(): array
    {
        return [];
    }

    abstract protected function handle(): string;

    abstract protected function options(): array;

    protected function parseRule(string $rule): array
    {
        $parameters = true;
        if (str_contains($rule, ':')) {
            list($rule, $parameter) = explode(':', $rule, 2);
            $parameters = str_getcsv($parameter);
        }
        return [$rule, $parameters];
    }

    protected function parseRules(): array
    {
        $rules = $this->validation;
        foreach ($rules as $key => $rule) {
            $rules[$key] = is_string($rule)
                ? explode('|', $rule)
                : $rule;
        }
        return $rules;
    }

    abstract protected function type(): string;

    protected function validation(): array
    {
        return [];
    }
}
