<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomSelect extends Field
{
    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Custom: Select', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'label',
            'name',
            'options',
            'placeholder',
            'required',
            'responsive_width',
            'tag',
            'tag_label',
            'type',
            'value',
        ];
    }

    protected function type(): string
    {
        return 'select';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
            'options' => 'required',
        ];
    }
}
