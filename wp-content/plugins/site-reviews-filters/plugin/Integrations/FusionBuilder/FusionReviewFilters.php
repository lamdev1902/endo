<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Integrations\FusionBuilder;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Addon\Filters\Shortcodes\SiteReviewsFiltersShortcode;
use GeminiLabs\SiteReviews\Integrations\FusionBuilder\FusionElement;

class FusionReviewFilters extends FusionElement
{
    public static function elementParameters(): array
    {
        return [
            'hide' => [
                'default' => '',
                'heading' => esc_html_x('Hide Fields', 'admin-text', 'site-reviews-filters'),
                'param_name' => 'hide',
                'placeholder_text' => esc_attr_x('Select Fields to Hide', 'admin-text', 'site-reviews-filters'),
                'type' => 'multiple_select',
                'value' => glsr(SiteReviewsFiltersShortcode::class)->getHideOptions(),
            ],
            'class' => [
                'heading' => esc_attr_x('CSS Class', 'admin-text', 'site-reviews-filters'),
                'description' => esc_attr_x('Add a class to the wrapping HTML element.', 'admin-text', 'site-reviews-filters'),
                'param_name' => 'class',
                'type' => 'textfield',
                'value' => '',
            ],
            'id' => [
                'heading' => esc_attr_x('CSS ID', 'admin-text', 'site-reviews-filters'),
                'description' => esc_attr_x('Add an ID to the wrapping HTML element.', 'admin-text', 'site-reviews-filters'),
                'param_name' => 'id',
                'type' => 'textfield',
                'value' => '',
            ],
            'reviews_id' => [
                'heading' => esc_attr_x('Reviews CSS ID', 'admin-text', 'site-reviews-filters'),
                'description' => esc_attr_x('Link filters to a Latest Reviews element which has this CSS ID.', 'admin-text', 'site-reviews-filters'),
                'param_name' => 'reviews_id',
                'type' => 'textfield',
                'value' => '',
            ],
        ];
    }

    public static function registerElement(): void
    {
        $parameters = static::elementParameters();
        $parameters = glsr(Application::class)->filterArray('fusion-builder/controls', $parameters);
        fusion_builder_map(fusion_builder_frontend_data(static::class, [
            'name' => esc_attr_x('Review Filters', 'admin-text', 'site-reviews-filters'),
            'shortcode' => 'site_reviews_filters',
            'icon' => 'fusiona-af-rating',
            'params' => $parameters,
        ]));
    }
}
