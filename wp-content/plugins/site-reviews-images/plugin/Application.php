<?php

namespace GeminiLabs\SiteReviews\Addon\Images;

use GeminiLabs\SiteReviews\Addons\Addon;

final class Application extends Addon
{
    public const ID = 'site-reviews-images';
    public const LICENSED = true;
    public const NAME = 'Review Images';
    public const SLUG = 'images';

    public function imageModal(): string
    {
        return !$this->option('disable_modal', false, 'bool')
            ? $this->option('modal', 'modal')
            : '';
    }
}
