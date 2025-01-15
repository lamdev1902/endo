<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Integrations\FusionBuilder;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Helpers\Arr;

class Controller extends AbstractController
{
    /**
     * @filter site-reviews/fusion-builder/controls/site_review
     */
    public function filterSiteReviewControls(array $parameters): array
    {
        $option = $this->optionForm(esc_attr_x('Use a Custom Review Theme', 'admin-text', 'site-reviews-themes'));
        $parameters = Arr::insertAfter('post_id', $parameters, ['theme' => $option]);
        $parameters['hide']['dependency'] = [
            [
                'element' => 'theme',
                'operator' => 'is_empty',
            ],
        ];
        return $parameters;
    }

    /**
     * @filter site-reviews/fusion-builder/controls/site_reviews
     */
    public function filterSiteReviewsControls(array $parameters): array
    {
        $option = $this->optionForm(esc_attr_x('Use a Custom Review Theme', 'admin-text', 'site-reviews-themes'));
        $parameters = Arr::prepend($parameters, $option, 'theme');
        $parameters['hide']['dependency'] = [
            [
                'element' => 'theme',
                'operator' => 'is_empty',
            ],
        ];
        return $parameters;
    }

    /**
     * @filter site-reviews/fusion-builder/controls/site_reviews_form
     */
    public function filterSiteReviewsFormControls(array $parameters): array
    {
        $option = $this->optionForm(esc_attr_x('Use a Custom Review Theme', 'admin-text', 'site-reviews-themes'));
        $parameters = Arr::prepend($parameters, $option, 'theme');
        return $parameters;
    }

    protected function optionForm(string $heading, string $description = ''): array
    {
        $themes = glsr(Application::class)->posts(-1, esc_attr_x('- Use Default Theme -', 'admin-text', 'site-reviews-themes'));
        if (count($themes) > 50) {
            $option = [
                'ajax' => 'fusion_search_query',
                'ajax_params' => [
                    'post_type' => ['name' => Application::POST_TYPE],
                ],
                'default' => '',
                'heading' => $heading,
                'max_input' => 1,
                'param_name' => 'theme',
                'placeholder_text' => esc_attr_x('Select or Leave Blank', 'admin-text', 'site-reviews-themes'),
                'type' => 'ajax_select',
                'value' => '',
            ];
        } else {
            $option = [
                'default' => '',
                'heading' => $heading,
                'param_name' => 'theme',
                'type' => 'select',
                'value' => $themes,
            ];
        }
        if (!empty($description)) {
            $option['description'] = $description;
        }
        return $option;
    }
}
