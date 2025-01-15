<?php

namespace GeminiLabs\SiteReviews\Addon\Actions;

use GeminiLabs\SiteReviews\Addon\Filters\SqlModifier;

class SqlOrderBy extends SqlModifier
{
    protected function buildSortBy(string $key, string $value): void
    {
        if ('useful' === $value) {
            $this->values = ['r.score desc', 'r.rating desc', 'p.post_date desc'];
        }
    }
}
