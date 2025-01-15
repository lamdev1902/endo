<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

class CustomRangeTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'span');
    }

    protected function value(): string
    {
        $options = $this->field->get('options');
        $value = filter_var($this->value, FILTER_SANITIZE_NUMBER_INT); // returns a string
        return $options[$value] ?? '';
    }
}
