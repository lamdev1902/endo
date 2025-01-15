<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Database;

use GeminiLabs\SiteReviews\Addon\Images\Defaults\GridImagesDefaults;
use GeminiLabs\SiteReviews\Addon\Images\GridImage;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Sql;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Modules\Style;

/**
 * @property array $args
 * @property \wpdb $db
 */
class Query
{
    use Sql;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

    public function reviewImage(int $offset, array $args = []): ?GridImage
    {
        $offset = Cast::toInt($offset);
        if ($offset < 0) {
            return null;
        }
        $this->setArgs(wp_parse_args(['offset' => $offset, 'per_page' => 1], $args));
        $results = glsr(Database::class)->dbGetResults($this->queryReviewImages(), ARRAY_A);
        foreach ($results as &$image) {
            $image = wp_parse_args(['index' => $this->args['offset']], $image);
            $image = new GridImage($image);
        }
        return !empty($results[0])
            ? $results[0]
            : null;
    }

    public function reviewImages(array $args = []): array
    {
        $this->setArgs($args);
        $results = [];
        if ($this->args['per_page'] > 0) { // allow zero results
            $results = glsr(Database::class)->dbGetResults($this->queryReviewImages(), ARRAY_A);
        }
        $total = $this->totalReviewImages($args);
        $currentIndex = (($this->args['page'] - 1) * $this->args['per_page']) + $this->args['offset'];
        foreach ($results as &$image) {
            $image = wp_parse_args(['index' => $currentIndex++], $image);
            $image = new GridImage($image);
        }
        $buttonClass = glsr(Style::class)->classes('button');
        $buttonClass = glsr(Sanitizer::class)->sanitizeAttrClass($buttonClass);
        return [
            'button_class' => $buttonClass,
            'count' => count($results),
            'max_pages' => (int) ceil($total / max(1, $this->args['per_page'])),
            'page' => $this->args['page'],
            'per_page' => $this->args['per_page'],
            'results' => $results,
            'total' => $total,
        ];
    }

    public function setArgs(array $args = [], array $unset = []): void
    {
        $args = glsr(GridImagesDefaults::class)->restrict($args);
        foreach ($unset as $key) {
            $args[$key] = '';
        }
        $this->args = $args;
    }

    public function totalReviewImages(array $args = []): int
    {
        $this->setArgs($args);
        return (int) glsr(Database::class)->dbGetVar($this->queryTotalReviewImages());
    }

    protected function clauseJoinStatus(): string
    {
        return "INNER JOIN {$this->db->posts} AS p ON (p.ID = r.review_id)";
    }

    protected function queryReviewImages(): string
    {
        return $this->sql("
            SELECT img.ID, r.review_id
            FROM table|posts AS img
            INNER JOIN table|ratings AS r ON (r.review_id = img.post_parent)
            {$this->sqlJoin()}
            {$this->sqlWhere()}
            AND img.post_type = 'attachment'
            ORDER BY p.post_date DESC, img.menu_order ASC
            {$this->sqlLimit()}
            {$this->sqlOffset()}
        ");
    }

    protected function queryTotalReviewImages(): string
    {
        return $this->sql("
            SELECT COUNT(DISTINCT img.ID) AS count
            FROM table|posts AS img
            INNER JOIN table|ratings AS r ON (r.review_id = img.post_parent)
            {$this->sqlJoin()}
            {$this->sqlWhere()}
            AND img.post_type = 'attachment'
        ");
    }
}
