<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class ReviewAssignedUsers extends Field
{
    public string $name = 'assigned_users';
    public string $tag = 'assigned_users';

    protected function conditionOperators(): array
    {
        return ['contains', 'equals', 'not'];
    }

    protected function handle(): string
    {
        return _x('Review: Assigned Users', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'conditions',
            'description',
            'hidden',
            'hidden:tag_label',
            'hidden:type',
            'hidden:users',
            'label',
            'placeholder',
            'required',
            'responsive_width',
            'tag_label',
            'roles',
            'type',
        ];
    }

    protected function type(): string
    {
        return 'review_assigned_users';
    }

    protected function validation(): array
    {
        return [
            'conditions' => 'criteria',
            'name' => 'required|slug',
        ];
    }
}
