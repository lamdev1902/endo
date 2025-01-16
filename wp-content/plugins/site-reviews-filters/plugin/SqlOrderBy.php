<?php

namespace GeminiLabs\SiteReviews\Addon\Filters;

class SqlOrderBy extends SqlModifier
{
    protected function buildSortBy(string $key, string $value): void
    {
        $orderByOptions = [
            'rating' => ['r.rating desc', 'p.post_date desc'],
            // 'recent' => ['p.post_date desc'],
        ];
        if (array_key_exists($value, $orderByOptions)) {
            $this->values = $orderByOptions[$value];
        }
    }
}
