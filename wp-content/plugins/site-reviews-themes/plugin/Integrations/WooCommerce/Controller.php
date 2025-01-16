<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Integrations\WooCommerce;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Controllers\AbstractController;

class Controller extends AbstractController
{
    /**
     * @filter site-reviews/settings
     */
    public function filterSettings(array $settings): array
    {
        if (!isset($settings['settings.integrations.woocommerce.style'])) {
            return $settings;
        }
        $themes = glsr(Application::class)->posts();
        if (empty($themes)) {
            return $settings;
        }
        $setting = $settings['settings.integrations.woocommerce.style'];
        $options = [
            _x('Styles', 'admin-text', 'site-reviews-themes') => $setting['options'],
            _x('Themes', 'admin-text', 'site-reviews-themes') => $themes,
        ];
        $setting['options'] = $options;
        $settings['settings.integrations.woocommerce.style'] = $setting;
        return $settings;
    }
}
