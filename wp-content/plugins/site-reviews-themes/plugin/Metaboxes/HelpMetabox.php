<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Metaboxes;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Columns\ShortcodeColumn;
use GeminiLabs\SiteReviews\Contracts\MetaboxContract;

class HelpMetabox implements MetaboxContract
{
    /**
     * @param \WP_Post $post
     */
    public function register($post): void
    {
        $title = _x('How To Use', 'admin-text', 'site-reviews-themes');
        add_meta_box('helpdiv', $title, [$this, 'render'], Application::POST_TYPE, 'side');
    }

    /**
     * @param \WP_Post $post
     */
    public function render($post): void
    {
        $shortcode = glsr(ShortcodeColumn::class, ['postId' => $post->ID]);
        glsr()->render(Application::ID.'/views/metabox-help', [
            'site_reviews' => $shortcode->build('site_reviews'),
            'site_reviews_form' => $shortcode->build('site_reviews_form'),
            'site_reviews_summary' => $shortcode->build('site_reviews_summary'),
        ]);
    }
}
