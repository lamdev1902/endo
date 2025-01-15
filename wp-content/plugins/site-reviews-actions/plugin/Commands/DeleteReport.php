<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Commands;

use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Modules\Notice;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;

class DeleteReport extends AbstractCommand
{
    public Request $request;
    public Review $review;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->review = glsr_get_review($this->request->review_id);
    }

    public function handle(): void
    {
        if (!$this->deleteRecord()) {
            glsr(Notice::class)->addError(_x('Unable to delete report.', 'admin-text', 'site-reviews-actions'));
            $this->fail();
            return;
        }
        if (0 !== $this->countRecords()) {
            return;
        }
        if (!$this->unflagReview()) {
            glsr(Notice::class)->addError(_x('Unable to unflag review.', 'admin-text', 'site-reviews-actions'));
            $this->fail();
            return;
        }
        glsr(Notice::class)->addSuccess(_x('The review has been unflagged.', 'admin-text', 'site-reviews-actions'));
    }

    public function response(): array
    {
        return [
            'notices' => glsr(Notice::class)->get(),
            'reports' => $this->countRecords(),
        ];
    }

    protected function countRecords(): int
    {
        $sql = "
            SELECT COUNT(DISTINCT ID) AS count
            FROM table|actions_log
            WHERE 1=1
            AND action = 'report'
            AND rating_id = %d
        ";
        return (int) glsr(Database::class)->dbGetVar(
            glsr(Query::class)->sql($sql, $this->review->rating_id)
        );
    }

    protected function deleteRecord(): bool
    {
        $values = [
            'action' => 'report',
            'ID' => $this->request->cast('log_id', 'int'),
            'rating_id' => $this->review->rating_id,
        ];
        if (0 === $values['ID']) {
            unset($values['ID']); // delete all reports for this review
        }
        $result = glsr(Database::class)->delete('actions_log', $values);
        return false !== $result;
    }

    protected function unflagReview(): bool
    {
        $result = glsr(Database::class)->update('ratings',
            ['is_flagged' => false],
            ['ID' => $this->review->rating_id]
        );
        return false !== $result;
    }
}
