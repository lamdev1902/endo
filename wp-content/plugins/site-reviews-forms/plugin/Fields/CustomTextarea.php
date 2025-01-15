<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomTextarea extends Field
{
    public function formats(): array
    {
        return [
            'ul' => 'Bulleted List (item per paragraph)',
            'excerpt' => 'Excerpt (expandable paragraph)',
            'paragraph' => 'Multiple Paragraphs',
            'ol' => 'Numbered List (item per paragraph)',
        ];
    }

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function defaults(): array
    {
        return [
            'format' => 'excerpt',
        ];
    }

    protected function handle(): string
    {
        return _x('Custom: Textarea', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'format',
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
        return 'textarea';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
        ];
    }
}
