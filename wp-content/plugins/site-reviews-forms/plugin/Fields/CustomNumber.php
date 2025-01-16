<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomNumber extends Field
{
    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'greater', 'less', 'not'];
    }

    protected function handle(): string
    {
        return _x('Custom: Number', 'admin-text', 'site-reviews-forms');
    }

    protected function type(): string
    {
        return 'number';
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'label',
            'name',
            'placeholder',
            'required',
            'responsive_width',
            'tag',
            'tag_label',
            'type',
            'value',
        ];
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
            'value' => 'number',
        ];
    }
}
