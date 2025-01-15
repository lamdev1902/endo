<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

class CustomMultiTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'span');
    }
}
