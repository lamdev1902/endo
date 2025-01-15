<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class RatingTag extends Tag
{
    protected function handle(): string
    {
        $numEmpty = abs(5 - Cast::toInt($this->value()));
        $numFull = abs(5 - $numEmpty);
        $fullStar = glsr(Builder::class)->span([
            'class' => 'glsr-rating-level glsr-rating-full',
        ]);
        $emptyStar = glsr(Builder::class)->span([
            'class' => 'glsr-rating-level glsr-rating-empty',
        ]);
        return glsr(Builder::class)->div([
            'class' => 'glsr-themed-rating',
            'data-rating' => $this->value(),
            'text' => str_repeat($fullStar, $numFull).str_repeat($emptyStar, $numEmpty),
        ]);
    }

    protected function value(): string
    {
        return '4';
    }
}
