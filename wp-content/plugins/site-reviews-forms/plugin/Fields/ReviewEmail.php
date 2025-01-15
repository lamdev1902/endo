<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class ReviewEmail extends Field
{
    public string $name = 'email';
    public string $tag = 'email';

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Review: Email', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'label',
            'placeholder',
            'required',
            'responsive_width',
            'tag_label',
            'type',
        ];
    }

    protected function type(): string
    {
        return 'review_email';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
            'name' => 'required|slug|unique',
            'type' => 'unique',
        ];
    }
}
