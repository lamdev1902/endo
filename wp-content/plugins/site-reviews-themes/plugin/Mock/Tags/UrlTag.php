<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class UrlTag extends Tag
{
    protected function value(): string
    {
        return '<a href="javascript:void(0)">https://website.com</a>';
    }
}
