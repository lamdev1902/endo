<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Widgets;

use GeminiLabs\SiteReviews\Addon\Images\Shortcodes\SiteReviewsImagesShortcode;
use GeminiLabs\SiteReviews\Contracts\ShortcodeContract;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Widgets\Widget;

class SiteReviewsImagesWidget extends Widget
{
    /**
     * @param array $instance
     * @return string
     */
    public function form($instance)
    {
        $this->widgetArgs = $this->shortcode()->normalize($instance)->args;
        $terms = glsr(Database::class)->terms();
        $this->renderField('text', [
            'label' => _x('Title', 'admin-text', 'site-reviews-images'),
            'name' => 'title',
        ]);
        $this->renderField('number', [
            'default' => 8,
            'label' => _x('How many images would you like to display?', 'admin-text', 'site-reviews-images'),
            'max' => 100,
            'name' => 'display',
        ]);
        $this->renderField('select', [
            'label' => _x('What is the minimum rating to display?', 'admin-text', 'site-reviews-images'),
            'name' => 'rating',
            'options' => [
                '0' => esc_attr(sprintf(_nx('%s star', '%s stars', 0, 'admin-text', 'site-reviews-images'), 0)),
                '1' => esc_attr(sprintf(_nx('%s star', '%s stars', 1, 'admin-text', 'site-reviews-images'), 1)),
                '2' => esc_attr(sprintf(_nx('%s star', '%s stars', 2, 'admin-text', 'site-reviews-images'), 2)),
                '3' => esc_attr(sprintf(_nx('%s star', '%s stars', 3, 'admin-text', 'site-reviews-images'), 3)),
                '4' => esc_attr(sprintf(_nx('%s star', '%s stars', 4, 'admin-text', 'site-reviews-images'), 4)),
                '5' => esc_attr(sprintf(_nx('%s star', '%s stars', 5, 'admin-text', 'site-reviews-images'), 5)),
            ],
        ]);
        if (count($reviewTypes = glsr()->retrieveAs('array', 'review_types')) > 1) {
            $this->renderField('select', [
                'label' => _x('Which type of review would you like to display?', 'admin-text', 'site-reviews-images'),
                'name' => 'type',
                'options' => Arr::prepend($reviewTypes, _x('All Reviews', 'admin-text', 'site-reviews-images'), ''),
            ]);
        }
        if (!empty($terms)) {
            $this->renderField('select', [
                'label' => _x('Limit reviews to this category', 'admin-text', 'site-reviews-images'),
                'name' => 'assigned_terms',
                'options' => Arr::prepend($terms, _x('Do not assign a category', 'admin-text', 'site-reviews-images'), ''),
            ]);
        }
        $this->renderField('text', [
            'default' => '',
            'description' => sprintf(_x("You may also enter %s to use the Post ID of the current page.", 'admin-text', 'site-reviews-images'), '<code>post_id</code>'),
            'label' => _x('Limit reviews to those assigned to a Post ID', 'admin-text', 'site-reviews-images'),
            'name' => 'assigned_posts',
        ]);
        $this->renderField('text', [
            'default' => '',
            'description' => sprintf(esc_html_x("You may also enter %s to use the ID of the logged-in user.", 'admin-text', 'site-reviews-images'), '<code>user_id</code>'),
            'label' => _x('Limit reviews to those assigned to a User ID', 'admin-text', 'site-reviews-images'),
            'name' => 'assigned_users',
        ]);
        $this->renderField('text', [
            'label' => _x('Enter any custom CSS classes here', 'admin-text', 'site-reviews-images'),
            'name' => 'class',
        ]);
        $this->renderField('checkbox', [
            'name' => 'hide',
            'options' => $this->shortcode()->getHideOptions(),
        ]);
        return ''; // WP_Widget::form should return a string
    }

    /**
     * @param array $newInstance
     * @param array $oldInstance
     * @return array
     */
    public function update($newInstance, $oldInstance)
    {
        if (!is_numeric($newInstance['display'])) {
            $newInstance['display'] = 10;
        }
        $newInstance['display'] = min(50, max(0, intval($newInstance['display'])));
        return parent::update($newInstance, $oldInstance);
    }

    protected function shortcode(): ShortcodeContract
    {
        return glsr(SiteReviewsImagesShortcode::class);
    }

    protected function widgetDescription(): string
    {
        return _x('Site Reviews: Display a gallery of your review images.', 'admin-text', 'site-reviews-images');
    }

    protected function widgetName(): string
    {
        return _x('Review Images', 'admin-text', 'site-reviews-images');
    }
}
