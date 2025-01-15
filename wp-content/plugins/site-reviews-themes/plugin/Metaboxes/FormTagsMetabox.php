<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Metaboxes;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Theme;
use GeminiLabs\SiteReviews\Contracts\MetaboxContract;

class FormTagsMetabox implements MetaboxContract
{
    /**
     * @param \WP_Post $post
     */
    public function register($post): void
    {
        $title = _x('Template Tags', 'admin-text', 'site-reviews-themes');
        add_meta_box('formtagsdiv', $title, [$this, 'render'], Application::POST_TYPE, 'side');
    }

    /**
     * @param \WP_Post $post
     */
    public function render($post): void
    {
        $tooltip = _x('Select a form to change the template tags. You can create your own custom forms with the %s add-on.', 'admin-text', 'site-reviews-themes');
        $tooltip = sprintf($tooltip, sprintf('<a href="%s">%s</a>', glsr_admin_url('addons'), _x('Review Forms', 'admin-text', 'site-reviews-themes')));
        glsr()->render(Application::ID.'/views/metabox-tags', [
            'form_id' => get_post_meta(get_the_ID(), '_form', true),
            'forms' => glsr(Theme::class)->forms(),
            'tooltip' => $tooltip,
        ]);
    }
}
