<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Integrations\FusionBuilder;

use GeminiLabs\SiteReviews\Controllers\AbstractController;

class Controller extends AbstractController
{
    /**
     * @filter site-reviews/enqueue/public/inline-script/after:20
     */
    public function filterPublicInlineScript(string $script): string
    {
        $search = 'fusion-element-render-site_reviews_summary';
        $replace = "{$search} fusion-element-render-site_reviews_images";
        $script = str_replace($search, $replace, $script);
        return $script;
    }

    /**
     * @action site-reviews-images/activated
     */
    public function onActivated(): void
    {
        fusion_builder_auto_activate_element('site_reviews_images');
    }

    /**
     * @action fusion_builder_before_init
     */
    public function registerFusionElements(): void
    {
        FusionReviewImages::registerElement();
    }
}
