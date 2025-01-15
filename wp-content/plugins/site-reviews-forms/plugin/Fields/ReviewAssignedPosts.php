<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class ReviewAssignedPosts extends Field
{
    public string $name = 'assigned_posts';
    public string $tag = 'assigned_links';

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Review: Assigned Posts', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'hidden',
            'hidden:tag_label',
            'hidden:type',
            'hidden:value',
            'label',
            'placeholder',
            'posttypes',
            'required',
            'responsive_width',
            'tag_label',
            'type',
        ];
    }

    protected function type(): string
    {
        return 'review_assigned_posts';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
            'name' => 'required|slug',
            'posttypes' => 'required',
        ];
    }
}
