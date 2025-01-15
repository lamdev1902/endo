<?php

namespace GeminiLabs\SiteReviews\Addon\Forms;

use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Database\Search\AbstractSearch;
use GeminiLabs\SiteReviews\Helpers\Str;

class SearchForms extends AbstractSearch
{
    public function forms(): array
    {
        $posts = [];
        foreach ($this->results as $result) {
            $posts[] = get_post($result->id);
        }
        return $posts;
    }

    protected function postStatuses(): string
    {
        $statuses = array_keys(get_post_stati([
            'protected' => true,
            'show_in_admin_all_list' => true,
        ]));
        $statuses[] = 'private';
        $statuses[] = 'publish';
        return Str::join($statuses, true);
    }

    protected function searchById(int $searchId): array
    {
        $sql = $this->db->prepare("
            SELECT p.ID as id, p.post_title as name
            FROM {$this->db->posts} AS p
            WHERE 1=1
            AND p.ID = %d
            AND p.post_type = %s
            AND p.post_status IN ({$this->postStatuses()})
        ", $searchId, Application::POST_TYPE);
        return glsr(Database::class)->dbGetResults(
            glsr(Query::class)->sql($sql)
        );
    }

    protected function searchByTerm(string $searchTerm): array
    {
        $like = '%'.$this->db->esc_like($searchTerm).'%';
        $sql = $this->db->prepare("
            SELECT p.ID as id, p.post_title as name
            FROM {$this->db->posts} AS p
            WHERE 1=1
            AND p.post_title LIKE %s
            AND p.post_type = %s
            AND p.post_status IN ({$this->postStatuses()})
            ORDER BY p.post_title LIKE %s DESC, p.post_date DESC
            LIMIT 20
        ", $like, Application::POST_TYPE, $like);
        return glsr(Database::class)->dbGetResults(
            glsr(Query::class)->sql($sql)
        );
    }
}
