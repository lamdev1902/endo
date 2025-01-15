<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Fields;

class CustomHidden extends Field
{
    protected function handle(): string
    {
        return _x('Custom: Hidden', 'admin-text', 'site-reviews-forms');
    }

    protected function options(): array
    {
        return [
            'name',
            'type',
            'value',
        ];
    }

    protected function type(): string
    {
        return 'hidden';
    }
}
