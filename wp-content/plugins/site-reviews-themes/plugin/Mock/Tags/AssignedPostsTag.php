<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class AssignedPostsTag extends Tag
{
    protected function value(): string
    {
        return _x('Dog Park', 'admin-text', 'site-reviews-themes');
    }
}
