<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Integrations\FusionBuilder;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Addon\Images\Shortcodes\SiteReviewsImagesShortcode;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Integrations\FusionBuilder\FusionElement;
use GeminiLabs\SiteReviews\Modules\Rating;

class FusionReviewImages extends FusionElement
{
    public static function elementParameters(): array
    {
        return [
            'assigned_posts' => [
                'default' => '',
                'heading' => esc_attr_x('Limit Reviews to an Assigned Page', 'admin-text', 'site-reviews-images'),
                'param_name' => 'assigned_posts',
                'type' => 'multiple_select',
                'placeholder_text' => esc_attr_x('Select or Leave Blank', 'admin-text', 'site-reviews-images'),
                'value' => [
                    'custom' => esc_attr_x('Specific Post ID', 'admin-text', 'site-reviews-images'),
                    'post_id' => esc_attr_x('The Current Page', 'admin-text', 'site-reviews-images'),
                    'parent_id' => esc_attr_x('The Parent Page', 'admin-text', 'site-reviews-images'),
                ],
            ],
            'assigned_posts_custom' => [
                'heading' => esc_attr_x('Assigned Post IDs', 'admin-text', 'site-reviews-images'),
                'description' => esc_attr_x('Separate values with a comma.', 'admin-text', 'site-reviews-images'),
                'param_name' => 'assigned_posts_custom',
                'type' => 'textfield',
                'value' => '',
                'dependency' => [
                    [
                        'element' => 'assigned_posts',
                        'value' => 'custom',
                        'operator' => 'contains',
                    ],
                ],
            ],
            'assigned_terms' => static::optionAssignedTerms(esc_attr_x('Limit Reviews to an Assigned Category', 'admin-text', 'site-reviews-images')),
            'assigned_users' => [
                'default' => '',
                'heading' => esc_attr_x('Limit Reviews to an Assigned User', 'admin-text', 'site-reviews-images'),
                'param_name' => 'assigned_users',
                'placeholder_text' => esc_attr_x('Select or Leave Blank', 'admin-text', 'site-reviews-images'),
                'type' => 'multiple_select',
                'value' => [
                    'custom' => esc_attr_x('Specific User ID', 'admin-text', 'site-reviews-images'),
                    'user_id' => esc_attr_x('The Logged-in user', 'admin-text', 'site-reviews-images'),
                    'author_id' => esc_attr_x('The Page author', 'admin-text', 'site-reviews-images'),
                    'profile_id' => esc_attr_x('The Profile user (BuddyPress/Ultimate Member)', 'admin-text', 'site-reviews-images'),
                ],
            ],
            'assigned_users_custom' => [
                'heading' => esc_attr_x('Assigned User IDs', 'admin-text', 'site-reviews-images'),
                'description' => esc_attr_x('Separate values with a comma.', 'admin-text', 'site-reviews-images'),
                'param_name' => 'assigned_users_custom',
                'type' => 'textfield',
                'value' => '',
                'dependency' => [
                    [
                        'element' => 'assigned_users',
                        'value' => 'custom',
                        'operator' => 'contains',
                    ],
                ],
            ],
            'terms' => [
                'default' => '',
                'heading' => esc_attr_x('Limit Reviews to Terms?', 'admin-text', 'site-reviews-images'),
                'param_name' => 'terms',
                'type' => 'select',
                'value' => [
                    '' => esc_attr_x('No', 'admin-text', 'site-reviews-images'),
                    'true' => esc_attr_x('Terms were accepted', 'admin-text', 'site-reviews-images'),
                    'false' => esc_attr_x('Terms were not accepted', 'admin-text', 'site-reviews-images'),
                ],
            ],
            'type' => static::optionReviewTypes(),
            'display' => [
                'default' => 8,
                'heading' => esc_html_x('Maximum number of images to display', 'admin-text', 'site-reviews-images'),
                'max' => 50,
                'min' => 1,
                'param_name' => 'display',
                'type' => 'range',
                'value' => 8,
            ],
            'rating' => [
                'default' => 0,
                'heading' => esc_html_x('Minimum Rating', 'admin-text', 'site-reviews-images'),
                'max' => Cast::toInt(glsr()->constant('MAX_RATING', Rating::class)),
                'min' => Cast::toInt(glsr()->constant('MIN_RATING', Rating::class)),
                'param_name' => 'rating',
                'type' => 'range',
                'value' => 0,
            ],
            'hide' => [
                'default' => '',
                'heading' => esc_html_x('Hide Fields', 'admin-text', 'site-reviews-images'),
                'param_name' => 'hide',
                'placeholder_text' => esc_attr_x('Select Fields to Hide', 'admin-text', 'site-reviews-images'),
                'type' => 'multiple_select',
                'value' => glsr(SiteReviewsImagesShortcode::class)->getHideOptions(),
            ],
            'class' => [
                'heading' => esc_attr_x('CSS Class', 'admin-text', 'site-reviews-images'),
                'description' => esc_attr_x('Add a class to the wrapping HTML element.', 'admin-text', 'site-reviews-images'),
                'param_name' => 'class',
                'type' => 'textfield',
                'value' => '',
            ],
            'id' => [
                'heading' => esc_attr_x('CSS ID', 'admin-text', 'site-reviews-images'),
                'description' => esc_attr_x('Add an ID to the wrapping HTML element.', 'admin-text', 'site-reviews-images'),
                'param_name' => 'id',
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
            'name' => esc_attr_x('Review Images', 'admin-text', 'site-reviews-images'),
            'shortcode' => 'site_reviews_images',
            'icon' => 'fusiona-af-rating',
            'params' => $parameters,
        ]));
    }
}
