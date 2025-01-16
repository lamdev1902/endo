<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Integrations\WooCommerce;

use GeminiLabs\SiteReviews\Hooks\AbstractHooks;

class Hooks extends AbstractHooks
{
    public function run(): void
    {
        $this->hook(Controller::class, [
            ['filterSettings', 'site-reviews/settings'],
        ]);
    }
}
