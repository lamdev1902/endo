<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Tinymce;

use GeminiLabs\SiteReviews\Tinymce\TinymceGenerator;

class SiteReviewsImagesTinymce extends TinymceGenerator
{
    public function fields(): array
    {
        return [
            [
                'html' => sprintf('<p class="strong">%s</p>', esc_html_x('All settings are optional.', 'admin-text', 'site-reviews-images')),
                'minWidth' => 320,
                'type' => 'container',
            ],
            [
                'label' => esc_html_x('Title', 'admin-text', 'site-reviews-images'),
                'name' => 'title',
                'tooltip' => _x('Enter a custom shortcode heading.', 'admin-text', 'site-reviews-images'),
                'type' => 'textbox',
            ],
            [
                'label' => esc_html_x('Classes', 'admin-text', 'site-reviews-images'),
                'name' => 'class',
                'tooltip' => _x('Add custom CSS classes to the shortcode.', 'admin-text', 'site-reviews-images'),
                'type' => 'textbox',
            ],
            [
                'columns' => 2,
                'items' => $this->hideOptions(),
                'label' => esc_html_x('Hide', 'admin-text', 'site-reviews-images'),
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
        return esc_html_x('Review Images', 'admin-text', 'site-reviews-images');
    }
}
