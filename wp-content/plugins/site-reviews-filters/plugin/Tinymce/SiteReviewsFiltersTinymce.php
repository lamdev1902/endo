<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Tinymce;

use GeminiLabs\SiteReviews\Tinymce\TinymceGenerator;

class SiteReviewsFiltersTinymce extends TinymceGenerator
{
    public function fields(): array
    {
        return [
            [
                'html' => sprintf('<p class="strong">%s</p>', esc_html_x('All settings are optional.', 'admin-text', 'site-reviews-filters')),
                'minWidth' => 320,
                'type' => 'container',
            ],
            [
                'label' => esc_html_x('Title', 'admin-text', 'site-reviews-filters'),
                'name' => 'title',
                'tooltip' => _x('Enter a custom shortcode heading.', 'admin-text', 'site-reviews-filters'),
                'type' => 'textbox',
            ],
            [
                'label' => esc_html_x('Reviews ID', 'admin-text', 'site-reviews-filters'),
                'name' => 'reviews_id',
                'tooltip' => _x('Enter the ID of the reviews block or shortcode that you want to filter.', 'admin-text', 'site-reviews-filters'),
                'type' => 'textbox',
            ],
            [
                'label' => esc_html_x('Classes', 'admin-text', 'site-reviews-filters'),
                'name' => 'class',
                'tooltip' => _x('Add custom CSS classes to the shortcode.', 'admin-text', 'site-reviews-filters'),
                'type' => 'textbox',
            ],
            [
                'columns' => 2,
                'items' => $this->hideOptions(),
                'label' => esc_html_x('Hide', 'admin-text', 'site-reviews-filters'),
                'layout' => 'grid',
                'spacing' => 5,
                'type' => 'container',
            ],
            [
                'hidden' => true,
                'name' => 'id',
                'type' => 'textbox',
            ]
        ];
    }

    public function title(): string
    {
        return _x('Review Filters', 'admin-text', 'site-reviews-filters');
    }
}
