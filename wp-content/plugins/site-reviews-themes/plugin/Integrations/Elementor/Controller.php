<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Integrations\Elementor;

use Elementor\Controls_Manager;
use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Helpers\Arr;

class Controller extends AbstractController
{
    /**
     * @param \Elementor\Widget_Base $widget
     *
     * @filter site-reviews/elementor/register/controls
     */
    public function filterLayoutControls(array $sections, $widget): array
    {
        $shortcodes = [
            'site_review',
            'site_reviews',
        ];
        if (!in_array($widget->get_name(), $shortcodes)) {
            return $sections;
        }
        $alert = [
            'condition' => [
                'theme!' => '',
            ],
            'content' => esc_html_x('This widget is using the layout style of the custom theme selected in the widget\'s Content settings.', 'admin-text', 'site-reviews-themes'),
            'type' => Controls_Manager::ALERT,
        ];
        $controls = $sections['style_layout']['controls'];
        $controls = Arr::prepend($controls, $alert, 'layout_alert');

        if (!empty($controls['alignment'])) {
            $controls['alignment']['condition'] = [
                'theme' => '',
            ];
        }
        if (!empty($controls['spacing'])) {
            $controls['spacing']['condition'] = [
                'theme' => '',
            ];
        }
        $sections['style_layout']['controls'] = $controls;
        return $sections;
    }

    /**
     * @param \Elementor\Widget_Base $widget
     *
     * @filter site-reviews/elementor/register/controls
     */
    public function filterRatingControls(array $sections, $widget): array
    {
        $shortcodes = [
            'site_review',
            'site_reviews',
            'site_reviews_form',
            'site_reviews_summary',
        ];
        if (!in_array($widget->get_name(), $shortcodes)) {
            return $sections;
        }
        $alert = [
            'condition' => [
                'theme!' => '',
            ],
            'content' => esc_html_x('This widget is using the rating style of the custom theme selected in the widget\'s Content settings.', 'admin-text', 'site-reviews-themes'),
            'type' => Controls_Manager::ALERT,
        ];
        $controls = $sections['style_rating']['controls'];
        $controls = Arr::prepend($controls, $alert, 'rating_alert');
        if (!empty($controls['rating_color'])) {
            $controls['rating_color']['condition'] = [
                'theme' => '',
            ];
        }
        if (!empty($controls['rating_size']) && 'site_reviews_form' !== $widget->get_name()) {
            $controls['rating_size']['condition'] = [
                'theme' => '',
            ];
        }
        $sections['style_rating']['controls'] = $controls;
        return $sections;
    }

    /**
     * @param \Elementor\Widget_Base $widget
     *
     * @filter site-reviews/elementor/register/controls
     */
    public function filterWidgetControls(array $sections, $widget): array
    {
        $shortcodes = [
            'site_review',
            'site_reviews',
            'site_reviews_form',
            'site_reviews_images',
            'site_reviews_summary',
        ];
        if (!in_array($widget->get_name(), $shortcodes)) {
            return $sections;
        }
        $option = [
            'default' => '',
            'label' => _x('Use a Custom Theme', 'admin-text', 'site-reviews-themes'),
            'label_block' => true,
            'options' => glsr(Application::class)->posts(),
            'type' => Controls_Manager::SELECT2,
        ];
        $controls = $sections['settings']['controls'];
        if (array_key_exists('form', $controls)) {
            $option['description'] = _x('This overrides the Custom Form Review Template.', 'admin-text', 'site-reviews-themes');
        }
        if ('site_review' === $widget->get_name() && array_key_exists('post_id', $controls)) {
            $controls['post_id']['separator'] = 'after';
            $controls = Arr::insertAfter('post_id', $controls, ['theme' => $option]);
        } else {
            $controls = Arr::prepend($controls, $option, 'theme');
        }
        $sections['settings']['controls'] = $controls;
        return $sections;
    }
}
