<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Database;

use GeminiLabs\SiteReviews\Addon\Forms\CustomField;
use GeminiLabs\SiteReviews\Addon\Forms\Defaults\QueryFieldDefaults;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Sql;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Rating;

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

    public function fields(array $args = []): array
    {
        $this->setArgs($args);
        $results = glsr(Database::class)->dbGetResults($this->queryFields(), ARRAY_A);
        return $this->normalizeFields($results);
    }

    public function setArgs(array $args = [], array $unset = []): void
    {
        $args = glsr(QueryFieldDefaults::class)->restrict($args);
        foreach ($unset as $key) {
            $args[$key] = '';
        }
        $this->args = $args;
    }

    protected function clauseAndField(): string
    {
        return $this->db->prepare('AND pm.meta_key = %s', sprintf('_custom_%s', $this->args['field']));
    }

    protected function clauseAndForm(): string
    {
        return $this->db->prepare("AND pm2.meta_key = '_custom_form' AND pm2.meta_value = %s", $this->args['form']);
    }

    protected function clauseJoinField(): string
    {
        return "INNER JOIN table|postmeta AS pm ON (pm.post_id = r.review_id)";
    }

    protected function clauseJoinForm(): string
    {
        return "INNER JOIN table|postmeta AS pm2 ON (pm2.post_id = r.review_id)";
    }

    protected function normalizeFields(array $results = []): array
    {
        $normalized = [];
        $field = new CustomField($this->args);
        if ('range' === $field->original_type) {
            $emptyArray = array_fill_keys(array_keys($field->options), 0);
        } elseif ('rating' === $field->original_type) {
            $emptyArray = glsr(Rating::class)->emptyArray();
        }
        foreach ($results as $result) {
            $count = Cast::toInt($result['count'] ?? 0);
            $type = $result['type'] ?? 'local';
            $value = Cast::toInt($result['value'] ?? 0);
            if (!array_key_exists($type, $normalized)) {
                $normalized[$type] = $emptyArray ?? [];
            }
            if (array_key_exists($value, $normalized[$type])) {
                $normalized[$type][$value] = $count;
            }
        }
        return $normalized;
    }

    protected function queryFields(): string
    {
        return $this->sql("
            SELECT CAST(pm.meta_value AS DECIMAL) AS value, r.type, COUNT(DISTINCT r.ID) AS count
            FROM table|ratings AS r
            {$this->sqlJoin()}
            {$this->sqlWhere()}
            GROUP BY r.type, pm.meta_value
        ");
    }
}
