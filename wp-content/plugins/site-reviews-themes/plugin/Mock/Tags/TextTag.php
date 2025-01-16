<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

class TextTag extends Tag
{
    protected function value(): string
    {
        return sprintf('{{ %s }}', $this->tag);
    }
}
