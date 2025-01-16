<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Widgets;

use GeminiLabs\SiteReviews\Addon\Filters\Shortcodes\SiteReviewsFiltersShortcode;
use GeminiLabs\SiteReviews\Contracts\ShortcodeContract;
use GeminiLabs\SiteReviews\Widgets\Widget;

class SiteReviewsFiltersWidget extends Widget
{
    /**
     * @param array $instance
     * @return string
     */
    public function form($instance)
    {
        $this->widgetArgs = $this->shortcode()->normalize($instance)->args;
        $this->renderField('text', [
            'class' => 'widefat',
            'label' => _x('Title', 'admin-text', 'site-reviews-filters'),
            'name' => 'title',
        ]);
        $this->renderField('text', [
            'class' => 'widefat',
            'description' => esc_html_x('Enter the ID of the reviews block or shortcode that you want to filter.', 'admin-text', 'site-reviews-filters'),
            'label' => _x('Reviews ID', 'admin-text', 'site-reviews-filters'),
            'name' => 'reviews_id',
        ]);
        $this->renderField('text', [
            'class' => 'widefat',
            'label' => _x('Enter any custom CSS classes here', 'admin-text', 'site-reviews-filters'),
            'name' => 'class',
        ]);
        $this->renderField('checkbox', [
            'name' => 'hide',
            'options' => $this->shortcode()->getHideOptions(),
        ]);
        return ''; // WP_Widget::form should return a string
    }

    protected function shortcode(): ShortcodeContract
    {
        return glsr(SiteReviewsFiltersShortcode::class);
    }

    protected function widgetDescription(): string
    {
        return _x('Site Reviews: Filter, search, and sort your reviews.', 'admin-text', 'site-reviews-filters');
    }

    protected function widgetName(): string
    {
        return _x('Review Filters', 'admin-text', 'site-reviews-filters');
    }
}
