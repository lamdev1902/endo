<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Integrations\FusionBuilder;

use GeminiLabs\SiteReviews\Integrations\FusionBuilder\Hooks as IntegrationHooks;

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
            ['filterSiteReviewControls', 'site-reviews/fusion-builder/controls/site_review'],
            ['filterSiteReviewsControls', 'site-reviews/fusion-builder/controls/site_reviews'],
            ['filterSiteReviewsFormControls', 'site-reviews/fusion-builder/controls/site_reviews_form'],
            ['onActivated', 'site-reviews/activated'],
            ['registerFusionElements', 'fusion_builder_before_init'],
        ]);
    }
}
