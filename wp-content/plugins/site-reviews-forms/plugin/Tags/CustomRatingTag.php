<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

class CustomRatingTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'div');
    }

    protected function value(): string
    {
        return glsr_star_rating($this->value, 0, $this->args->toArray());
    }
}
