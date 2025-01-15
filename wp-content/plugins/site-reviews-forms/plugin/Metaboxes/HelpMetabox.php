<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Metaboxes;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\Columns\ShortcodeColumn;
use GeminiLabs\SiteReviews\Contracts\MetaboxContract;

class HelpMetabox implements MetaboxContract
{
    /**
     * @param \WP_Post $post
     */
    public function register($post): void
    {
        $id = Application::POST_TYPE.'-helpdiv';
        $title = _x('How to Use', 'admin-text', 'site-reviews-forms');
        add_meta_box($id, $title, [$this, 'render'], Application::POST_TYPE, 'side', 'low');
    }

    /**
     * @param \WP_Post $post
     */
    public function render($post): void
    {
        $column = glsr(ShortcodeColumn::class, ['postId' => $post->ID]);
        glsr(Application::class)->render('views/metabox-help', [
            'site_reviews' => $column->build('site_reviews'),
            'site_reviews_form' => $column->build('site_reviews_form'),
        ]);
    }
}
