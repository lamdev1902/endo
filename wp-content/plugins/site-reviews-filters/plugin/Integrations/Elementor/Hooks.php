<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Integrations\Elementor;

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
            ['filterLatestReviewsControls', 'site-reviews/elementor/register/controls', 10, 2],
            ['filterLatestReviewsDisplaySettings', 'site-reviews/elementor/display/settings', 10, 2],
            ['filterSummaryControls', 'site-reviews/elementor/register/controls', 10, 2],
            ['registerElementorWidgets', 'elementor/widgets/register'],
        ]);
    }
}
