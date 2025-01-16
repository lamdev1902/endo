<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Integrations\Elementor;

use GeminiLabs\SiteReviews\Integrations\Elementor\Hooks as IntegrationHooks;

class Hooks extends IntegrationHooks
{
    public function run(): void
    {
        if (!$this->isInstalled()) {
            return;
        }
        if (!$this->isVersionSupported()) {
            return;
        }
        $this->hook(Controller::class, [
            ['filterLayoutControls', 'site-reviews/elementor/register/controls', 10, 2],
            ['filterRatingControls', 'site-reviews/elementor/register/controls', 10, 2],
            ['filterWidgetControls', 'site-reviews/elementor/register/controls', 10, 2],
        ]);
    }
}
