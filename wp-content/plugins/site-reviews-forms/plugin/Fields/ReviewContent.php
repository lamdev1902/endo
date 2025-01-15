<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class ReviewContent extends Field
{
    public string $name = 'content';
    public string $tag = 'content';

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Review: Content', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'label',
            'maxlength',
            'minlength',
            'placeholder',
            'required',
            'responsive_width',
            'tag_label',
            'type',
        ];
    }

    protected function type(): string
    {
        return 'review_content';
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
