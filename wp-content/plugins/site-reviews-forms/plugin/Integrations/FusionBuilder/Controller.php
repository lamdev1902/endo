<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Integrations\FusionBuilder;

use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Helpers\Arr;

class Controller extends AbstractController
{
    /**
     * @filter site-reviews/fusion-builder/controls/site_review
     */
    public function filterSiteReviewControls(array $parameters): array
    {
        $option = FusionFieldSummary::optionForm(
            esc_attr_x('Use a Custom Review Form Template', 'admin-text', 'site-reviews-forms')
        );
        $parameters = Arr::insertAfter('post_id', $parameters, ['form' => $option]);
        return $parameters;
    }

    /**
     * @filter site-reviews/fusion-builder/controls/site_reviews
     */
    public function filterSiteReviewsControls(array $parameters): array
    {
        $option = FusionFieldSummary::optionForm(
            esc_attr_x('Use a Custom Review Form Template', 'admin-text', 'site-reviews-forms')
        );
        $parameters = Arr::prepend($parameters, $option, 'form');
        return $parameters;
    }

    /**
     * @filter site-reviews/fusion-builder/controls/site_reviews_form
     */
    public function filterSiteReviewsFormControls(array $parameters): array
    {
        $option = FusionFieldSummary::optionForm(
            esc_attr_x('Use a Custom Review Form', 'admin-text', 'site-reviews-forms')
        );
        $parameters = Arr::prepend($parameters, $option, 'form');
        $parameters['hide']['dependency'] = [
            [
                'element' => 'form',
                'operator' => 'is_empty',
            ],
        ];
        return $parameters;
    }

    /**
     * @action site-reviews/activated
     */
    public function onActivated(): void
    {
        fusion_builder_auto_activate_element('site_reviews_field');
    }

    /**
     * @action fusion_builder_before_init
     */
    public function registerFusionElements(): void
    {
        FusionFieldSummary::registerElement();
    }
}
