<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomText extends Field
{
    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Custom: Text', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'label',
            'maxlength',
            'minlength',
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

    protected function type(): string
    {
        return 'text';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
        ];
    }
}
