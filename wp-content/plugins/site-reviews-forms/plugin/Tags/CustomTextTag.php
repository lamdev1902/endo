<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

class CustomTextTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'span');
    }
}
