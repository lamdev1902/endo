<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomRange extends Field
{
    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Custom: Range', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'label',
            'labels',
            'name',
            'options',
            'required',
            'responsive_width',
            'tag',
            'tag_label',
            'type',
        ];
    }

    protected function type(): string
    {
        return 'range';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
            'options' => 'required',
        ];
    }
}
