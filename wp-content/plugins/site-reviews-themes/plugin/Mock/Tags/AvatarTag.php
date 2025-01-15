<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

use GeminiLabs\SiteReviews\Addon\Themes\Application;

class AvatarTag extends Tag
{
    protected function value(): string
    {
        $image = file_get_contents(
            glsr(Application::class)->path('assets/images/icons/avatar.svg')
        );
        return trim($image);
    }
}
