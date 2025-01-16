<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Commands;

use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;
use GeminiLabs\SiteReviews\Shortcodes\SiteReviewsFormShortcode;

class UpvoteReview extends AbstractCommand
{
    public Review $review;

    protected $errors;
    protected $message;
    protected $title;

    public function __construct(Request $request)
    {
        $this->review = glsr_get_review($request->cast('review_id', 'int'));
    }

    public function handle(): void
    {
        if ('user' === glsr_get_option('addons.actions.upvote_restricted') && !is_user_logged_in()) {
            $loginLink = glsr(SiteReviewsFormShortcode::class)->loginLink();
            $this->message =  sprintf(__('You must be %s to upvote a review.', 'site-reviews-actions'), $loginLink);
            $this->title = __('Not allowed', 'site-reviews-actions');
            return;
        }
        if (is_user_logged_in() && $this->review->author_id === get_current_user_id()) {
            $this->message = __('You cannot upvote your own review.', 'site-reviews-actions');
            $this->title = __('Not allowed', 'site-reviews-actions');
            return;
        }
        $recordIds = $this->queryRecords();
        if (empty($recordIds)) {
            $this->increaseScore();
            $this->insertRecord();
        } else {
            $this->decreaseScore();
            $this->removeRecords($recordIds);
        }
    }

    public function response(): array
    {
        return [
            'message' => $this->message ?? '',
            'score' => $this->review->score,
            'title' => $this->title ?? '',
        ];
    }

    protected function decreaseScore(): void
    {
        $score = max(0, --$this->review->score);
        glsr(Database::class)->update('ratings',
            ['score' => $score],
            ['ID' => $this->review->rating_id]
        );
        $this->review->set('score', $score);
    }

    protected function increaseScore(): void
    {
        $score = ++$this->review->score;
        glsr(Database::class)->update('ratings',
            ['score' => $score],
            ['ID' => $this->review->rating_id]
        );
        $this->review->set('score', $score);
    }

    protected function insertRecord(): void
    {
        glsr(Database::class)->insert('actions_log', [
            'action' => 'upvote',
            'date' => current_time('mysql'),
            'ip_address' => Helper::getIpAddress(),
            'rating_id' => $this->review->rating_id,
            'user_id' => get_current_user_id(),
        ]);
    }

    protected function queryRecords(): array
    {
        $userId = get_current_user_id() ?: -1;
        $ipAddress = Helper::getIpAddress();
        $sql = "
            SELECT al.ID
            FROM table|actions_log AS al
            WHERE al.rating_id = '{$this->review->rating_id}' 
            AND (al.user_id = %d OR al.ip_address = %s)
        ";
        return glsr(Database::class)->dbGetCol(
            glsr(Query::class)->sql($sql, $userId, $ipAddress)
        );
    }

    protected function removeRecords(array $ids): void
    {
        $ids = implode(',', $ids);
        $sql = glsr(Query::class)->sql("
            DELETE FROM table|actions_log WHERE ID IN ({$ids})
        ");
        glsr(Database::class)->dbQuery($sql);
    }
}
