<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomUrl extends Field
{
    public function formats(): array
    {
        return [
            'link' => 'Link',
            'link_blank' => 'Link (open in new tab/window)',
            'plain' => 'Plain Text',
        ];
    }

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function defaults(): array
    {
        return [
            'format' => 'link',
        ];
    }

    protected function handle(): string
    {
        return _x('Custom: URL', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'format',
            'format_link_text',
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

    protected function type(): string
    {
        return 'url';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
        ];
    }
}
