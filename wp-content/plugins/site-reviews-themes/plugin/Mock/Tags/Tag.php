<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

use GeminiLabs\SiteReviews\Modules\Html\Tags\Tag as AbstractTag;

class Tag extends AbstractTag
{
    public function isHidden(string $path = ''): bool
    {
        return false;
    }
}
