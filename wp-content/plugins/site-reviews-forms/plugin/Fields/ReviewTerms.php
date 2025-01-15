<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class ReviewTerms extends Field
{
    public string $name = 'terms';
    public string $tag = '';

    protected function conditionOperators(): array
    {
        return ['equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Review: Terms', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'label',
            'required', 
            'responsive_width',
            'type',
        ];
    }

    protected function type(): string
    {
        return 'review_terms';
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
