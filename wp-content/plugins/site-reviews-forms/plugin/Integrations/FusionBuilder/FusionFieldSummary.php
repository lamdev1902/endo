<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Integrations\FusionBuilder;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\Shortcodes\SiteReviewsFieldShortcode;
use GeminiLabs\SiteReviews\Integrations\FusionBuilder\FusionElement;

class FusionFieldSummary extends FusionElement
{
    public static function elementParameters(): array
    {
        return [
            'form' => static::optionForm(
                esc_attr_x('Review Form', 'admin-text', 'site-reviews-forms'),
                esc_attr_x('Select the custom review form.', 'admin-text', 'site-reviews-forms')
            ),
            'field' => [
                'heading' => esc_attr_x('Field Name', 'admin-text', 'site-reviews-forms'),
                'description' => esc_attr_x('Enter the field name of your custom form.', 'admin-text', 'site-reviews-forms'),
                'param_name' => 'field',
                'type' => 'textfield',
                'value' => '',
            ],
            'assigned_posts' => [
                'default' => '',
                'heading' => esc_attr_x('Limit Reviews to an Assigned Page', 'admin-text', 'site-reviews-forms'),
                'param_name' => 'assigned_posts',
                'type' => 'multiple_select',
                'placeholder_text' => esc_attr_x('Select or Leave Blank', 'admin-text', 'site-reviews-forms'),
                'value' => [
                    'custom' => esc_attr_x('Specific Post ID', 'admin-text', 'site-reviews-forms'),
                    'post_id' => esc_attr_x('The Current Page', 'admin-text', 'site-reviews-forms'),
                    'parent_id' => esc_attr_x('The Parent Page', 'admin-text', 'site-reviews-forms'),
                ],
            ],
            'assigned_posts_custom' => [
                'heading' => esc_attr_x('Assigned Post IDs', 'admin-text', 'site-reviews-forms'),
                'description' => esc_attr_x('Separate values with a comma.', 'admin-text', 'site-reviews-forms'),
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
            'assigned_terms' => static::optionAssignedTerms(esc_attr_x('Limit Reviews to an Assigned Category', 'admin-text', 'site-reviews-forms')),
            'assigned_users' => [
                'default' => '',
                'heading' => esc_attr_x('Limit Reviews to an Assigned User', 'admin-text', 'site-reviews-forms'),
                'param_name' => 'assigned_users',
                'placeholder_text' => esc_attr_x('Select or Leave Blank', 'admin-text', 'site-reviews-forms'),
                'type' => 'multiple_select',
                'value' => [
                    'custom' => esc_attr_x('Specific User ID', 'admin-text', 'site-reviews-forms'),
                    'user_id' => esc_attr_x('The Logged-in user', 'admin-text', 'site-reviews-forms'),
                    'author_id' => esc_attr_x('The Page author', 'admin-text', 'site-reviews-forms'),
                    'profile_id' => esc_attr_x('The Profile user (BuddyPress/Ultimate Member)', 'admin-text', 'site-reviews-forms'),
                ],
            ],
            'assigned_users_custom' => [
                'heading' => esc_attr_x('Assigned User IDs', 'admin-text', 'site-reviews-forms'),
                'description' => esc_attr_x('Separate values with a comma.', 'admin-text', 'site-reviews-forms'),
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
                'heading' => esc_attr_x('Limit Reviews to Terms?', 'admin-text', 'site-reviews-forms'),
                'param_name' => 'terms',
                'type' => 'select',
                'value' => [
                    '' => esc_attr_x('No', 'admin-text', 'site-reviews-forms'),
                    'true' => esc_attr_x('Terms were accepted', 'admin-text', 'site-reviews-forms'),
                    'false' => esc_attr_x('Terms were not accepted', 'admin-text', 'site-reviews-forms'),
                ],
            ],
            'type' => static::optionReviewTypes(),
            'hide' => [
                'default' => '',
                'heading' => esc_html_x('Hide Fields', 'admin-text', 'site-reviews-forms'),
                'param_name' => 'hide',
                'placeholder_text' => esc_attr_x('Select Fields to Hide', 'admin-text', 'site-reviews-forms'),
                'type' => 'multiple_select',
                'value' => glsr(SiteReviewsFieldShortcode::class)->getHideOptions(),
            ],
            'class' => [
                'heading' => esc_attr_x('CSS Class', 'admin-text', 'site-reviews-forms'),
                'description' => esc_attr_x('Add a class to the wrapping HTML element.', 'admin-text', 'site-reviews-forms'),
                'param_name' => 'class',
                'type' => 'textfield',
                'value' => '',
            ],
            'id' => [
                'heading' => esc_attr_x('CSS ID', 'admin-text', 'site-reviews-forms'),
                'description' => esc_attr_x('Add an ID to the wrapping HTML element.', 'admin-text', 'site-reviews-forms'),
                'param_name' => 'id',
                'type' => 'textfield',
                'value' => '',
            ],
        ];
    }

    public static function optionForm(string $heading, string $description = ''): array
    {
        $forms = glsr(Application::class)->posts(-1, esc_attr_x('- Use Default Form -', 'admin-text', 'site-reviews-forms'));
        if (count($forms) > 50) {
            $option = [
                'ajax' => 'fusion_search_query',
                'ajax_params' => [
                    'post_type' => ['name' => Application::POST_TYPE],
                ],
                'default' => '',
                'heading' => $heading,
                'max_input' => 1,
                'param_name' => 'form',
                'placeholder_text' => esc_attr_x('Select or Leave Blank', 'admin-text', 'site-reviews-forms'),
                'type' => 'ajax_select',
                'value' => '',
            ];
        } else {
            $option = [
                'default' => '',
                'heading' => $heading,
                'param_name' => 'form',
                'type' => 'select',
                'value' => $forms,
            ];
        }
        if (!empty($description)) {
            $option['description'] = $description;
        }
        return $option;
    }

    public static function registerElement(): void
    {
        $parameters = static::elementParameters();
        $parameters = glsr()->filterArray('fusion-builder/controls/site_reviews_field', $parameters);
        fusion_builder_map(fusion_builder_frontend_data(static::class, [
            'name' => esc_attr_x('Field Summary', 'admin-text', 'site-reviews-forms'),
            'shortcode' => 'site_reviews_field',
            'icon' => 'fusiona-af-rating',
            'params' => $parameters,
        ]));
    }
}
