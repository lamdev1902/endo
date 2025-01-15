<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Integrations\Elementor;

use Elementor\Controls_Manager;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Shortcodes\SiteReviewsShortcode;

class Controller extends AbstractController
{
    /**
     * @param \Elementor\Widget_Base $widget
     * @filter site-reviews/elementor/register/controls
     */
    public function filterLatestReviewsControls(array $sections, $widget): array
    {
        if ('site_reviews' !== $widget->get_name()) {
            return $sections;
        }
        $display = glsr(SiteReviewsShortcode::class)->getDisplayOptions();
        $controls = [];
        foreach ($display as $key => $label) {
            $controls['filter-'.$key] = [
                'label' => $label,
                'return_value' => '1',
                'type' => Controls_Manager::SWITCHER,
            ];
        }
        return Arr::insertAfter('settings', $sections, [
            'filter_settings' => [
                'label' => _x('Filters', 'admin-text', 'site-reviews-filters'),
                'controls' => $controls,
            ],
        ]);
    }

    /**
     * @param \Elementor\Widget_Base $widget
     * @filter site-reviews/elementor/display/settings
     */
    public function filterLatestReviewsDisplaySettings(array $settings, $widget): array
    {
        if ('site_reviews' !== $widget->get_name()) {
            return $settings;
        }
        $filter = [];
        foreach ($settings as $key => $value) {
            if (str_starts_with($key, 'filter-') && !empty($value)) {
                $filter[] = Str::removePrefix($key, 'filter-');
            }
        }
        $settings['filters'] = array_filter($filter);
        return $settings;
    }

    /**
     * @param \Elementor\Widget_Base $widget
     * @filter site-reviews/elementor/register/controls
     */
    public function filterSummaryControls(array $sections, $widget): array
    {
        if ('site_reviews_summary' !== $widget->get_name()) {
            return $sections;
        }
        $controls = [
            'filters' => [
                'label' => _x('Enable the filters?', 'admin-text', 'site-reviews-filters'),
                'return_value' => 'true',
                'type' => Controls_Manager::SWITCHER,
            ],
            'reviews_id' => [
                'default' => '',
                'description' => _x('Enter the Custom ID of a reviews widget to enable AJAX filtering.', 'admin-text', 'site-reviews-filters'),
                'label' => _x('Custom Reviews ID', 'admin-text', 'site-reviews-filters'),
                'label_block' => true,
                'separator' => 'before',
                'type' => Controls_Manager::TEXT,
            ],
        ];
        return Arr::insertAfter('settings', $sections, [
            'filter_settings' => [
                'label' => _x('Filters', 'admin-text', 'site-reviews-filters'),
                'controls' => $controls,
            ],
        ]);
    }

    /**
     * @param \Elementor\Widgets_Manager $manager
     * @action elementor/widgets/register
     */
    public function registerElementorWidgets($manager): void
    {
        $manager->register(new ElementorFilterWidget());
    }
}
