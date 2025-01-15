<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class TelTag extends Tag
{
    protected function value(): string
    {
        return '<a href="javascript:void(0)">+1 (234) 567-8900</a>';
    }
}
