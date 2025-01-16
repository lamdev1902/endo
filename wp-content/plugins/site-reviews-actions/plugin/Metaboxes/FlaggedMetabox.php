<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Metaboxes;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Addon\Actions\Defaults\ActionLogDefaults;
use GeminiLabs\SiteReviews\Contracts\MetaboxContract;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Review;

class FlaggedMetabox implements MetaboxContract
{
    /**
     * @param \WP_Post $post
     */
    public function register($post): void
    {
        $review = glsr_get_review($post->ID);
        if ($review->is_flagged) {
            $id = glsr()->post_type.'-flaggeddiv';
            $title = _x('Review Moderation', 'admin-text', 'site-reviews-actions');
            add_meta_box($id, $title, [$this, 'render'], null, 'normal', 'high');
        }
    }

    /**
     * @param \WP_Post $post
     */
    public function render($post): void
    {
        $review = glsr_get_review($post->ID);
        glsr(Application::class)->render('metaboxes/flagged', [
            'reports' => $this->reports($review),
        ]);
    }

    public function reports(Review $review): array
    {
        $sql = "
            SELECT *
            FROM table|actions_log
            WHERE 1=1
            AND action = 'report'
            AND rating_id = %d
        ";
        $results = glsr(Database::class)->dbGetResults(
            glsr(Query::class)->sql($sql, $review->rating_id)
        );
        $results = array_map(fn ($result) => glsr(ActionLogDefaults::class)->restrict($result), $results);
        return $results;
    }
}
