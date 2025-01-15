<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Controllers;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Review;

class ReviewFormsController extends AbstractController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @action admin_enqueue_scripts
     */
    public function enqueueAdminAssets(): void
    {
        if (!glsr()->isAdmin()) {
            return;
        }
        $screen = glsr_current_screen();
        if (empty(glsr()->addon('site-reviews-forms'))) {
            return;
        }
        if (glsr('site-reviews-forms')->post_type !== $screen->post_type || 'post' !== $screen->base) {
            return;
        }
        wp_enqueue_script(
            "{$this->app()->id}/hooks",
            $this->app()->url("assets/{$this->app()->id}-hooks.js"),
            ['site-reviews/admin'],
            $this->app()->version
        );
    }

    /**
     * @filter site-reviews-forms/defaults/field/casts
     */
    public function filterFieldDefaultCasts(array $casts): array
    {
        $casts['translatable'] = 'bool';
        return $casts;
    }

    /**
     * @param \GeminiLabs\SiteReviews\Addon\Forms\Fields\Field $field
     * @action site-reviews-forms/field
     */
    public function modifyTranslatableField($field): void
    {
        $translatable = ['checkbox', 'radio', 'select', 'text', 'textarea', 'toggle'];
        if (in_array($field->type, $translatable)) {
            $field->defaults['translatable'] = true;
            $field->options[] = 'translatable';
        }
    }

    /**
     * @param mixed $data
     * @action admin_footer
     */
    public function removeSavedTranslation(Review $review, $data): void
    {
        if (!empty($data)) {
            delete_post_meta($review->ID, '_en');
        }
    }

    /**
     * @action admin_footer
     */
    public function renderTemplates(): void
    {
        global $hook_suffix;
        if (!in_array($hook_suffix, ['post.php', 'post-new.php'])) {
            return;
        }
        if (empty(glsr()->addon('site-reviews-forms'))) {
            return;
        }
        if (glsr('site-reviews-forms')->post_type !== get_post_type()) {
            return;
        }
        $this->app()->render('views/templates');
    }
}
