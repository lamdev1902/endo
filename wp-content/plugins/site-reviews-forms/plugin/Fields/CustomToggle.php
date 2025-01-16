<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomToggle extends Field
{
    public function formats(): array
    {
        return [
            'ul' => 'Bulleted List',
            'comma' => 'Comma Separated Values',
            'ol' => 'Numbered List',
        ];
    }

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function defaults(): array
    {
        return [
            'format' => 'comma',
        ];
    }

    protected function handle(): string
    {
        return _x('Custom: Toggle', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'format',
            'label',
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
        return 'toggle';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
            'options' => 'required',
        ];
    }
}
