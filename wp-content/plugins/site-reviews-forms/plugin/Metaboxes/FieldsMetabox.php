<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Metaboxes;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\FormFields;
use GeminiLabs\SiteReviews\Contracts\MetaboxContract;
use GeminiLabs\SiteReviews\Helpers\Cast;

class FieldsMetabox implements MetaboxContract
{
    /**
     * @param \WP_Post $post
     */
    public function register($post): void
    {
        $id = Application::POST_TYPE.'-fieldsdiv';
        $title = _x('Form Fields', 'admin-text', 'site-reviews-forms');
        add_meta_box($id, $title, [$this, 'render'], Application::POST_TYPE, 'normal', 'high');
    }

    /**
     * @param \WP_Post $post
     */
    public function render($post): void
    {
        $fields = glsr(FormFields::class)->normalizedFieldsForMetaboxIndexed($post->ID);
        if (empty($fields)) {
            $fields = glsr(FormFields::class)->defaultFieldsForMetaboxIndexed();
        }
        // glsr_log($fields);
        glsr()->render(Application::ID.'/views/metabox-fields', [
            'fields' => json_encode($fields, JSON_HEX_APOS),
        ]);
    }

    /**
     * @param int $postId
     */
    public function save($postId): void
    {
        if ($fields = filter_input(INPUT_POST, 'fields')) {
            $fields = Cast::toArray(json_decode($fields));
            glsr(FormFields::class)->saveFields(Cast::toInt($postId), $fields);
        }
    }
}
