<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class ReviewTitle extends Field
{
    public string $name = 'title';
    public string $tag = 'title';

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Review: Title', 'admin-text', 'site-reviews-forms');
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
        return 'review_title';
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
