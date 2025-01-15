<?php

namespace GeminiLabs\SiteReviews\Addon\Themes;

use GeminiLabs\SiteReviews\Addons\Addon;

final class Application extends Addon
{
    public const ID = 'site-reviews-themes';
    public const LICENSED = true;
    public const NAME = 'Review Themes';
    public const POST_TYPE = 'site-review-theme';
    public const SLUG = 'themes';
}
