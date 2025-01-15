<?php

namespace GeminiLabs\SiteReviews\Addon\Forms;

use GeminiLabs\SiteReviews\Addons\Addon;

final class Application extends Addon
{
    public const ID = 'site-reviews-forms';
    public const LICENSED = true;
    public const NAME = 'Review Forms';
    public const POST_TYPE = 'site-review-form';
    public const SLUG = 'forms';
}
