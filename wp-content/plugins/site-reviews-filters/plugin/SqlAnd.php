<?php

namespace GeminiLabs\SiteReviews\Addon\Filters;

use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Helpers\Str;

class SqlAnd extends SqlModifier
{
    protected function buildFilterByRating(string $key, string $value): void
    {
        $queries = [
            'critical' => 'AND r.rating < 4',
            'positive' => 'AND r.rating > 3',
            'rating' => sprintf('AND r.rating = %d', (int) $value),
        ];
        $this->values[$key] = is_numeric($value)
            ? $queries['rating']
            : $queries[$value];
    }

    protected function buildSearchFor(string $key, string $value): void
    {
        $search = str_replace(["\r", "\n"], '', $value);
        $search = preg_replace('/&#(x)?0*(?(1)27|39);?/i', "'", $search); // decode single quotes
        $search = substr($search, 0, 50); // restrict search to the first 50 characters
        $like = "%".glsr(Query::class)->db->esc_like($search)."%";
        $setting = Str::restrictTo(['title', 'content'], glsr_get_option('addons.filters.search'));
        $searchFor = [
            '' => glsr(Query::class)->db->prepare('AND ((p.post_title LIKE %s) OR (p.post_content LIKE %s))', $like, $like),
            'content' => glsr(Query::class)->db->prepare('AND p.post_content LIKE %s', $like),
            'title' => glsr(Query::class)->db->prepare('AND p.post_title LIKE %s', $like),
        ];
        $this->values[$key] = $searchFor[strtolower($setting)];
    }
}
