<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Metaboxes;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\ReviewTemplate;
use GeminiLabs\SiteReviews\Contracts\MetaboxContract;

class TemplateTagsMetabox implements MetaboxContract
{
    /**
     * @param \WP_Post $post
     */
    public function register($post): void
    {
        $id = Application::POST_TYPE.'-templatetagsdiv';
        $title = _x('Reserved Tags', 'admin-text', 'site-reviews-forms');
        add_meta_box($id, $title, [$this, 'render'], Application::POST_TYPE, 'side', 'low');
    }

    /**
     * @param \WP_Post $post
     */
    public function render($post): void
    {
        glsr()->render(Application::ID.'/views/metabox-template-tags', [
            'tags' => glsr(ReviewTemplate::class)->reservedTags(),
        ]);
    }
}
