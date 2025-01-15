<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class ToggleTag extends Tag
{
    protected function value(): string
    {
        return _x('Yes', 'admin-text', 'site-reviews-themes');
    }
}
