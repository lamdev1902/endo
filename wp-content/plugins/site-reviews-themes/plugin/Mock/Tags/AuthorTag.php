<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

use GeminiLabs\SiteReviews\Helpers\Text;

class AuthorTag extends Tag
{
    protected function handle(): string
    {
        $format = glsr_get_option('reviews.name.format');
        $initial = glsr_get_option('reviews.name.initial');
        return Text::name($this->value(), $format, $initial);
    }

    protected function value(): string
    {
        return 'Jane Doe';
    }
}
