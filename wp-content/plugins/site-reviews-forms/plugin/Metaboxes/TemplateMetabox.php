<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Metaboxes;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\ReviewTemplate;
use GeminiLabs\SiteReviews\Contracts\MetaboxContract;
use GeminiLabs\SiteReviews\Helpers\Cast;

class TemplateMetabox implements MetaboxContract
{
    /**
     * @param \WP_Post $post
     */
    public function register($post): void
    {
        $id = Application::POST_TYPE.'-templatediv';
        $title = _x('Review Template', 'admin-text', 'site-reviews-forms');
        add_meta_box($id, $title, [$this, 'render'], Application::POST_TYPE, 'normal', 'high');
    }

    /**
     * @param \WP_Post $post
     */
    public function render($post): void
    {
        $template = glsr(ReviewTemplate::class)->normalizedTemplate(get_the_ID());
        glsr()->render(Application::ID.'/views/metabox-template', [
            'template' => $template,
        ]);
    }

    /**
     * @param int $postId
     */
    public function save($postId): void
    {
        $template = filter_input(INPUT_POST, 'review_template');
        glsr(ReviewTemplate::class)->save(
            Cast::toInt($postId),
            Cast::toString($template)
        );
    }
}
