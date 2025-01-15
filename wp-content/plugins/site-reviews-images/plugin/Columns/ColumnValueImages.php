<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Columns;

use GeminiLabs\SiteReviews\Contracts\ColumnValueContract;
use GeminiLabs\SiteReviews\Review;

class ColumnValueImages implements ColumnValueContract
{
    function handle(Review $review): string
    {
        return (string) count($review->images);
    }
}
