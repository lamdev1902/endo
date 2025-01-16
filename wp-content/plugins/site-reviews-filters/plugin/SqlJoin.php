<?php

namespace GeminiLabs\SiteReviews\Addon\Filters;

class SqlJoin extends SqlModifier
{
    protected function buildSearchFor(string $key): void
    {
        $this->values[$key] = "INNER JOIN table|posts AS p ON (p.ID = r.review_id)";
    }
}
