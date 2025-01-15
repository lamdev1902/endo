<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class AssignedLinksTag extends AssignedPostsTag
{
    protected function handle(): string
    {
        $link = sprintf('<a href="javascript:void(0)">%s</a>', $this->value());
        return sprintf(_x('Review of %s', 'admin-text', 'site-reviews-themes'), $link);
    }
}
