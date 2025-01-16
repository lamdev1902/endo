<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

class CustomTelTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'span');
    }

    protected function value(): string
    {
        $value = filter_var($this->value, FILTER_SANITIZE_NUMBER_INT); // returns a string
        if ('link' === $this->field->get('format', 'link')) {
            return sprintf('<a href="tel:%1$s">%1$s</a>', $value);
        }
        return $value;
    }
}
