<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Controllers;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\Commands\Export;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Request;

class ExportController extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @param string[] $actions
     * @filter bulk_actions-edit-{Application::POST_TYPE}
     */
    public function filterBulkActions(array $actions): array
    {
        if (glsr()->can('export')) {
            $actions['export'] = _x('Export', 'admin-text', 'site-reviews-forms');
            $order = ['edit', 'export', 'trash'];
            uksort($actions, fn ($a, $b) => array_search($a, $order) > array_search($b, $order) ? 1 : -1);
        }
        return $actions;
    }

    /**
     * @filter post_row_actions
     */
    public function filterRowActions(array $actions, \WP_Post $post): array
    {
        if (Application::POST_TYPE !== $post->post_type) {
            return $actions;
        }
        if (glsr()->can('export')) {
            $baseurl = admin_url("post.php?post={$post->ID}&plugin={$this->app()->id}");
            $actions['export'] = glsr(Builder::class)->a([
                'aria-label' => esc_attr(_x('Export this form', 'admin-text', 'site-reviews-forms')),
                'href' => wp_nonce_url(add_query_arg('action', 'export', $baseurl), "export-{$post->post_type}_{$post->ID}"),
                'text' => _x('Export', 'admin-text', 'site-reviews-forms'),
            ]);
            $order = ['edit', 'export', 'trash'];
            uksort($actions, fn ($a, $b) => array_search($a, $order) > array_search($b, $order) ? 1 : -1);
        }
        return $actions;
    }

    /**
     * @action handle_bulk_actions-edit-{Application::POST_TYPE}
     */
    public function onBulkExport(string $redirectUrl, string $doaction, array $postIds): void
    {
        if (empty($postIds)) {
            return;
        }
        $request = new Request([
            'post_ids' => $postIds,
        ]);
        $this->execute(new Export($request));
    }

    /**
     * @action admin_action_export
     */
    public function onExport(): void
    {
        if ($this->app()->id === filter_input(INPUT_GET, 'plugin')) {
            $postId = $this->getPostId();
            check_admin_referer("export-{$this->app()->post_type}_{$postId}");
            $request = new Request([
                'post_ids' => [$postId],
            ]);
            $this->execute(new Export($request));
        }
    }
}
