<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

use GeminiLabs\SiteReviews\Modules\Html\Builder;

class TitleTag extends Tag
{
    protected function handle(): string
    {
        return glsr(Builder::class)->h3($this->value());
    }

    protected function value(): string
    {
        return 'Excellent secure area';
    }
}
