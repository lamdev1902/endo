<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Controllers;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Columns\FormColumn;
use GeminiLabs\SiteReviews\Addon\Themes\Columns\ShortcodeColumn;
use GeminiLabs\SiteReviews\Addon\Themes\Columns\SlugColumn;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class ListTableController extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @param array $columns
     * @action manage_{Application::POST_TYPE}_posts_columns
     */
    public function filterColumnsForPostType($columns): array
    {
        return Arr::insertAfter('title', Arr::consolidate($columns), [
            'slug' => _x('Slug', 'Theme table columns (admin-text)', 'site-reviews-themes'),
            'form' => _x('Form', 'Theme table columns (admin-text)', 'site-reviews-themes'),
            // 'shortcode' => _x('Shortcode', 'Theme table columns (admin-text)', 'site-reviews-themes'),
        ]);
    }

    /**
     * @param array $hidden
     * @param \WP_Screen $screen
     * @return array
     * @filter default_hidden_columns
     */
    public function filterDefaultHiddenColumns($hidden, $screen): array
    {
        $hidden = Arr::consolidate($hidden);
        if ("edit-{$this->app()->post_type}" === Arr::get($screen, 'id')) {
            return array_unique(array_merge($hidden, ['slug']));
        }
        return $hidden;
    }

    /**
     * @filter post_row_actions
     */
    public function filterRowActions(array $actions, \WP_Post $post): array
    {
        if (Application::POST_TYPE !== $post->post_type) {
            return $actions;
        }
        unset($actions['inline hide-if-no-js']); // Remove Quick-edit
        $id = glsr(Builder::class)->span([
            'aria-label' => esc_attr(sprintf(_x('The theme ID is %d', 'admin-text', 'site-reviews-themes'), $post->ID)),
            'text' => sprintf(_x('ID: %d', '%d: Post ID (admin-text)', 'site-reviews-themes'), $post->ID),
        ]);
        $actions = Arr::prepend($actions, $id, 'id');
        return $actions;
    }

    /**
     * @action posts_search
     */
    public function filterSearchQuery(string $search, \WP_Query $query): string
    {
        if (!$this->hasQueryPermission($query, $this->app()->post_type)) {
            return $search;
        }
        if (!is_numeric($query->get('s')) || empty($search)) {
            return $search;
        }
        global $wpdb;
        $replace = $wpdb->prepare("{$wpdb->posts}.ID = %d", $query->get('s'));
        return str_replace('AND (((', "AND ((({$replace}) OR (", $search);
    }

    /**
     * @param string $column
     * @param int $postId
     * @action manage_{Application::POST_TYPE}_posts_custom_column
     */
    public function renderColumnValues($column, $postId): void
    {
        if ('form' === $column) {
            glsr(FormColumn::class, ['postId' => $postId])->render();
        } elseif ('slug' === $column) {
            glsr(SlugColumn::class, ['postId' => $postId])->render();
        }
    }
}
