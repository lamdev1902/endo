<?php

namespace GeminiLabs\SiteReviews\Addon\Filters;

use GeminiLabs\SiteReviews\Addon\Filters\Defaults\FilteredDefaults;
use GeminiLabs\SiteReviews\Contracts\FieldContract;
use GeminiLabs\SiteReviews\Modules\Html\Attributes;
use GeminiLabs\SiteReviews\Modules\Html\Form;
use GeminiLabs\SiteReviews\Modules\Style;

class FilterForm extends Form
{
    public function __construct(array $args = [])
    {
        $overrides = [
            'class' => 'glsr-filters-form',
        ];
        $values = glsr(FilteredDefaults::class)->defaults();
        parent::__construct(wp_parse_args($overrides, $args), $values);
    }

    public function config(): array
    {
        return glsr(Application::class)->config('forms/filters-form');
    }

    public function build(): string
    {
        return glsr(Template::class)->build('templates/filters-form', [
            'args' => $this->args,
            'context' => [
                'attributes' => $this->dataAttributes(),
                'class' => $this->classAttrForm(),
                'filter_by' => $this->buildFilterBy(),
                'search_for' => $this->buildSearchFor(),
                'sort_by' => $this->buildSortBy(),
            ],
            'form' => $this,
        ]);
    }

    public function field(string $name, array $args): FieldContract
    {
        $field = new FilterField(wp_parse_args($args, compact('name')));
        $this->normalizeField($field);
        return $field;
    }

    /**
     * @return FieldContract[]
     */
    public function fieldsFor(string $group): array
    {
        $fields = parent::fieldsFor($group);
        $fields = array_filter($fields, fn ($field) => !in_array($field->original_name, $this->args->hide));
        return $fields;
    }

    public function loadSession(array $values): void
    {
        $this->session = glsr()->args([
            'errors' => [],
            'values' => $values,
        ]);
    }

    protected function buildFields(): string
    {
        return '';
    }

    protected function buildFilterBy(): string
    {
        $fields = $this->fieldsFor('filter_by');
        $fields = array_reduce($fields, fn ($carry, $field) => $carry.$field->build(), '');
        if (empty($fields)) {
            return '';
        }
        return glsr(Template::class)->build('templates/filter-by', [
            'args' => $this->args,
            'context' => [
                'class' => 'glsr-filter-by',
                'fields' => $fields,
                'label' => __('Filter by', 'site-reviews-filters'),
            ],
        ]);
    }

    protected function buildSearchFor(): string
    {
        $fields = $this->fieldsFor('search_for');
        $fields = array_reduce($fields, fn ($carry, $field) => $carry.$field->build(), '');
        if (empty($fields)) {
            return '';
        }
        return glsr(Template::class)->build('templates/search-for', [
            'args' => $this->args,
            'context' => [
                'button_class' => glsr(Style::class)->classes('button'),
                'class' => 'glsr-search-for',
                'search' => $fields,
                'submit_text' => __('Search', 'site-reviews-filters'),
            ],
        ]);
    }

    protected function buildSortBy(): string
    {
        $fields = $this->fieldsFor('sort_by');
        $fields = array_reduce($fields, fn ($carry, $field) => $carry.$field->build(), '');
        if (empty($fields)) {
            return '';
        }
        return glsr(Template::class)->build('templates/sort-by', [
            'args' => $this->args,
            'context' => [
                'class' => 'glsr-sort-by',
                'fields' => $fields,
                'label' => __('Sort by', 'site-reviews-filters'),
            ],
        ]);
    }

    protected function dataAttributes(): string
    {
        return glsr(Attributes::class)
            ->set(glsr(FilteredDefaults::class)->dataAttributes())
            ->toString();
    }

    /**
     * Normalize the field with the form's session data.
     * Any normalization that is not specific to the form or session data
     * should be done in the field itself.
     */
    protected function normalizeField(FieldContract $field): void
    {
        $this->normalizeFieldChecked($field);
        $this->normalizeFieldGroup($field);
        $this->normalizeFieldId($field);
        $this->normalizeFieldValue($field);
    }

    protected function normalizeFieldGroup(FieldContract $field): void
    {
        $groups = [
            'filter_by', 'search_for', 'sort_by',
        ];
        foreach ($groups as $group) {
            if (str_starts_with($field->original_name, $group)) {
                $field->group = $group;
            }
        }
    }
}
