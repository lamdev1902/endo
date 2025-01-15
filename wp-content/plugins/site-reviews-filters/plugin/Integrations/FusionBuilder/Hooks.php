<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Integrations\FusionBuilder;

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
            ['filterPublicInlineScript', 'site-reviews/enqueue/public/inline-script/after', 20],
            ['onActivated', 'site-reviews-filters/activated'],
            ['registerFusionElements', 'fusion_builder_before_init'],
        ]);
    }
}
