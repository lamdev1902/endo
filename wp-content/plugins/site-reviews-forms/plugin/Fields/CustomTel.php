<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomTel extends Field
{
    public function formats(): array
    {
        return [
            'link' => 'Link (tel:)',
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
        return _x('Custom: Telephone', 'admin-text', 'site-reviews-forms');
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
        return 'tel';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
        ];
    }
}
