<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class AssignedUsersTag extends Tag
{
    protected function value(): string
    {
        return _x('Peter and Jane', 'admin-text', 'site-reviews-themes');
    }
}
