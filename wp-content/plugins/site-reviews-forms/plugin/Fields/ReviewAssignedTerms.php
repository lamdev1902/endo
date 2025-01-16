<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class ReviewAssignedTerms extends Field
{
    public string $name = 'assigned_terms';
    public string $tag = 'assigned_terms';

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Review: Categories', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'hidden',
            'hidden:tag_label',
            'hidden:terms',
            'hidden:type',
            'label',
            'placeholder',
            'required',
            'responsive_width',
            'tag_label',
            'terms',
            'type',
        ];
    }

    protected function type(): string
    {
        return 'review_assigned_terms';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
            'name' => 'required|slug',
        ];
    }
}
