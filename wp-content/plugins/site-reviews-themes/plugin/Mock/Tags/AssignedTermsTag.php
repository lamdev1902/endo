<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class AssignedTermsTag extends Tag
{
    protected function value(): string
    {
        return _x('People and Places', 'admin-text', 'site-reviews-themes');
    }
}
