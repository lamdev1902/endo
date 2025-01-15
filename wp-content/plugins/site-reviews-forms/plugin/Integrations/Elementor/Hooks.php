<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Integrations\Elementor;

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
            ['elementorWidgetFormFieldsAjax', 'site-reviews/route/ajax/elementor-widget-form-fields'],
            ['filterElementorWidgetControls', 'site-reviews/elementor/register/controls', 10, 2],
            ['registerElementorWidgets', 'elementor/widgets/register'],
            ['registerInlineStyles', 'elementor/editor/after_enqueue_styles'],
            ['registerInlineStyles', 'elementor/preview/enqueue_styles'],
            ['registerScripts', 'elementor/editor/after_enqueue_scripts'],
        ]);
    }
}
