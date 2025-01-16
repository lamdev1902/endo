<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Controllers;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Addon\Actions\Commands\DeleteReport;
use GeminiLabs\SiteReviews\Addon\Actions\Metaboxes\FlaggedMetabox;
use GeminiLabs\SiteReviews\Addon\Actions\Notices\FlaggedNotice;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Database\Tables;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Request;

class FlaggedController extends AbstractController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @action site-reviews/route/ajax/delete-report
     */
    public function deleteReportAjax(Request $request)
    {
        $command = $this->execute(new DeleteReport($request));
        if ($command->successful()) {
            wp_send_json_success($command->response());
        }
        wp_send_json_error($command->response());
    }

    /**
     * @filter site-reviews/dashboard/widget/data
     */
    public function filterDashboardData(array $data): array
    {
        $excludedTypes = get_post_stati(['show_in_admin_all_list' => false]);
        $excludedTypes = sprintf("'%s'", implode("','", $excludedTypes));
        $sql = glsr(Query::class)->sql("
            SELECT COUNT(*) AS count
            FROM table|ratings AS r
            INNER JOIN table|posts AS p ON (p.ID = r.review_id)
            WHERE 1=1
            AND p.post_status NOT IN ({$excludedTypes})
            AND r.is_flagged = 1
        ");
        $value = (int) glsr(Database::class)->dbGetVar($sql);
        $data['flagged'] = [
            'dashicon' => 'dashicons-flag',
            'label' => _nx('is flagged', 'are flagged', $value, '1 review is flagged; 2 reviews are flagged (admin-text)', 'site-reviews-actions'),
            'url' => add_query_arg('flagged', 1, glsr_admin_url()),
            'value' => $value,
        ];
        return $data;
    }

    /**
     * @param array $class
     * @param array $cssClass
     * @param int $postId
     * @filter post_class
     */
    public function filterListTableRowClass($class, $cssClass, $postId): array
    {
        $class = Arr::consolidate($class);
        $review = glsr_get_review($postId);
        if ($review->is_flagged) {
            $class[] = 'is-flagged';
        }
        return $class;
    }

    /**
     * @param array $views
     * @filter views_edit-site-review
     */
    public function filterListTableViews($views): array
    {
        $views = Arr::consolidate($views);
        $attributes = 1 === filter_input(INPUT_GET, 'flagged', FILTER_VALIDATE_INT)
            ? ' class="current" aria-current="page"'
            : '';
        $label = _x('Flagged <span class="count">(%s)</span>', 'admin-text', 'site-reviews-actions');
        $label = sprintf($label, number_format_i18n($this->queryFlaggedCount()));
        $url = add_query_arg('flagged', 1, glsr_admin_url());
        $views['flagged'] = sprintf('<a href="%s"%s>%s</a>', esc_url($url), $attributes, $label);
        return $views;
    }

    /**
     * @filter site-reviews/defaults/review/defaults
     */
    public function filterReviewDefaultsArray(array $defaults): array
    {
        $defaults['is_flagged'] = false;
        return $defaults;
    }

    /**
     * @filter site-reviews/defaults/review/sanitize
     */
    public function filterReviewSanitizeArray(array $sanitize): array
    {
        $sanitize['is_flagged'] = 'bool';
        return $sanitize;
    }

    /**
     * @filter site-reviews/review-table/clauses
     */
    public function filterReviewTableClauses(array $clauses): array
    {
        if (1 !== filter_input(INPUT_GET, 'flagged', FILTER_VALIDATE_INT)) {
            return $clauses;
        }
        $ratingsTable = glsr(Tables::class)->table('ratings');
        $postsTable = glsr(Tables::class)->table('posts');
        $clauses['join']['clauses'][] = "INNER JOIN {$ratingsTable} ON ({$ratingsTable}.review_id = {$postsTable}.ID)";
        $clauses['where']['clauses'][] = "AND {$ratingsTable}.is_flagged = 1";
        $clauses['where']['replace'] = false;
        return $clauses;
    }

    /**
     * @param \WP_Post $post
     * @action add_meta_boxes_{glsr()->post_type}
     */
    public function registerMetaBoxes($post): void
    {
        glsr(FlaggedMetabox::class)->register($post);
    }

    /**
     * @admin admin_head
     */
    public function renderFlaggedNotice(): void
    {
        glsr()->singleton(FlaggedNotice::class); // make singleton
        glsr(FlaggedNotice::class);
    }

    protected function queryFlaggedCount(): int
    {
        $excludedTypes = get_post_stati(['show_in_admin_all_list' => false]);
        $excludedTypes = sprintf("'%s'", implode("','", $excludedTypes));
        $sql = glsr(Query::class)->sql("
            SELECT COUNT(*) AS count
            FROM table|ratings AS r
            INNER JOIN table|posts AS p ON (p.ID = r.review_id)
            WHERE 1=1
            AND r.is_flagged = 1
            AND p.post_status NOT IN ({$excludedTypes})
        ");
        return (int) glsr(Database::class)->dbGetVar($sql);
    }
}
