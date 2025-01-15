<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Metaboxes;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\MetaboxContract;

class SubmitMetabox implements MetaboxContract
{
    /**
     * @param \WP_Post $post
     */
    public function register($post): void
    {
        remove_meta_box('submitdiv', Application::POST_TYPE, 'side');
        $title = _x('Theme Settings', 'admin-text', 'site-reviews-themes');
        add_meta_box('submitdiv', $title, [$this, 'render'], Application::POST_TYPE, 'side', 'high');
    }

    /**
     * @param \WP_Post $post
     */
    public function render($post): void
    {
        $deleteText = _x('Delete permanently', 'admin-text', 'site-reviews-themes');
        if (EMPTY_TRASH_DAYS) {
            $deleteText = _x('Move to Trash', 'admin-text', 'site-reviews-themes');
        }
        $postTypeObj = get_post_type_object($post->post_type);
        glsr()->render(Application::ID.'/views/metabox-settings', [
            'canPublish' => current_user_can($postTypeObj->cap->publish_posts),
            'deleteText' => $deleteText,
            'post' => $post,
            'postId' => (int) $post->ID,
        ]);
    }

    /**
     * @param int $postId
     */
    public function save($postId): void
    {
    }
}
