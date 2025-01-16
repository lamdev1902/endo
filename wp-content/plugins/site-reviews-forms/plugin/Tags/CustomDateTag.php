<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

class CustomDateTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'span');
    }

    protected function value(): string
    {
        $format = $this->field->get('format', 'F j, Y');
        return date_i18n($format, strtotime($this->value));
    }
}
