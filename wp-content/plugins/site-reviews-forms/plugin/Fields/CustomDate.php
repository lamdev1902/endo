<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomDate extends Field
{
    protected function defaults(): array
    {
        return [
            'format' => 'F j, Y',
        ];
    }

    protected function conditionOperators(): array
    {
        return ['equals', 'greater', 'less', 'not'];
    }

    protected function handle(): string
    {
        return _x('Custom: Date', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'format',
            'label',
            'name',
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
        return 'date';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
        ];
    }
}
