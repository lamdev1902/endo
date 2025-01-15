<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Settings;

use GeminiLabs\SiteReviews\Helpers\Arr;

abstract class Setting
{
    /**
     * The default values of the field, these are used to generate the field HTML in the form.
     * @var array
     */
    public $defaults;

    /**
     * The field handle is used in the Form UI if there is no label set.
     * @var string
     */
    public $handle;

    /**
     * The field [name] attribute key.
     * @var string
     */
    public $name = '';

    /**
     * The field options to display for this field type.
     * @var array
     */
    public $options = [];

    /**
     * The field tag is used in the review template to display the field value.
     * @var string
     */
    public $tag = '';

    /**
     * This is the actual field type which used to determine the HTML tag of the field.
     * @var string
     */
    public $type;

    /**
     * The validation rules for the field options.
     * @var array
     */
    public $validation;

    public function __construct()
    {
        $fields = glsr()->config('forms/review-form', false);
        $this->defaults = wp_parse_args($this->defaults(), [
            'handle' => $this->handle(),
            'label' => Arr::get($fields, $this->name.'.label'),
            'placeholder' => Arr::get($fields, $this->name.'.placeholder'),
            'name' => $this->name,
            'tag' => $this->tag,
            'type' => $this->type(),
        ]);
        $this->handle = $this->handle();
        $this->type = $this->type();
        $this->validation = wp_parse_args($this->validation(), [
            'name' => 'required|reserved|slug|unique',
            'tag' => 'reserved|slug|unique',
        ]);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return true;
    }

    /**
     * @return array
     */
    public function parsedValidation()
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

    /**
     * @return array
     */
    protected function defaults()
    {
        return [];
    }

    /**
     * @return string
     */
    abstract protected function handle();

    /**
     * @param string $rule
     * @return array
     */
    protected function parseRule($rule)
    {
        $parameters = true;
        if (str_contains($rule, ':')) {
            list($rule, $parameter) = explode(':', $rule, 2);
            $parameters = str_getcsv($parameter);
        }
        return [$rule, $parameters];
    }

    /**
     * @return array
     */
    protected function parseRules()
    {
        $rules = $this->validation;
        foreach ($rules as $key => $rule) {
            $rules[$key] = is_string($rule)
                ? explode('|', $rule)
                : $rule;
        }
        return $rules;
    }

    /**
     * @return string
     */
    abstract protected function type();

    /**
     * @return array
     */
    protected function validation()
    {
        return [];
    }
}
