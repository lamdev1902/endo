<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Controllers;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\ColumnFilterForm;
use GeminiLabs\SiteReviews\Addon\Forms\Columns\FieldsColumn;
use GeminiLabs\SiteReviews\Addon\Forms\Columns\ReviewsColumn;
use GeminiLabs\SiteReviews\Addon\Forms\Columns\ShortcodeColumn;
use GeminiLabs\SiteReviews\Addon\Forms\Columns\SlugColumn;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class ListTableController extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @filter site-reviews/defaults/column-filterby/defaults
     */
    public function filterColumnFilterby(array $defaults): array
    {
        $defaults['form'] = FILTER_SANITIZE_NUMBER_INT;
        return $defaults;
    }

    /**
     * @action pre_get_posts
     */
    public function filterColumnFilterQuery(\WP_Query $query): void
    {
        if (!$this->hasQueryPermission($query)) {
            return;
        }
        if ($filterBy = filter_input(INPUT_GET, 'form', FILTER_SANITIZE_NUMBER_INT)) {
            $meta = new \WP_Meta_Query($query->get('meta_query'));
            $meta->queries[] = ['key' => '_custom_form', 'value' => $filterBy];
            $query->set('meta_query', $meta->queries);
        }
    }

    /**
     * @param array $columns
     * @action manage_{Application::POST_TYPE}_posts_columns
     */
    public function filterColumnsForPostType($columns): array
    {
        return Arr::insertAfter('title', Arr::consolidate($columns), [
            'slug' => _x('Slug', 'Form table columns (admin-text)', 'site-reviews-forms'),
            'fields' => _x('Fields', 'Form table columns (admin-text)', 'site-reviews-forms'),
            'reviews' => _x('Reviews', 'Form table columns (admin-text)', 'site-reviews-forms'),
            // 'shortcode' => _x('Shortcode', 'Form table columns (admin-text)', 'site-reviews-forms'),
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
     * @filter site-reviews/defaults/listtable-filters
     */
    public function filterListtableFilters(array $filters): array
    {
        $filters['form'] = ColumnFilterForm::class;
        return $filters;
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
            'aria-label' => esc_attr(sprintf(_x('The form ID is %d', 'admin-text', 'site-reviews-forms'), $post->ID)),
            'text' => sprintf(_x('ID: %d', '%d: Post ID (admin-text)', 'site-reviews-forms'), $post->ID),
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
        $postId = Cast::toInt($postId);
        if ('fields' === $column) {
            glsr(FieldsColumn::class, ['postId' => $postId])->render();
        } elseif ('reviews' === $column) {
            glsr(ReviewsColumn::class, ['postId' => $postId])->render();
        // } elseif ('shortcode' === $column) {
        //     glsr(ShortcodeColumn::class, ['postId' => $postId])->render('site_reviews_form');
        } elseif ('slug' === $column) {
            glsr(SlugColumn::class, ['postId' => $postId])->render();
        }
    }
}
