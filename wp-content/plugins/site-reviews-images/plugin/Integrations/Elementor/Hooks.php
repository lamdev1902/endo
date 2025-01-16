<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Integrations\Elementor;

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
            ['registerElementorWidgets', 'elementor/widgets/register'],
        ]);
    }
}
