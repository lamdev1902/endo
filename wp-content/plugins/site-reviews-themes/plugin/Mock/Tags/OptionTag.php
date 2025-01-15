<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class OptionTag extends Tag
{
    protected function value(): string
    {
        return _x('Selected Value', 'admin-text', 'site-reviews-themes');
    }
}
