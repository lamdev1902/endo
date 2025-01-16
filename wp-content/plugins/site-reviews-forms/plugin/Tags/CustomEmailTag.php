<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

use GeminiLabs\SiteReviews\Modules\Html\Builder;

class CustomEmailTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'span');
    }

    protected function value(): string
    {
        $value = antispambot($this->value);
        if ('link' === $this->field->get('format', 'link')) {
            $value = glsr(Builder::class)->a([
                'href' => sprintf('mailto:%s', esc_url($value)),
                'text' => esc_url($value),
            ]);
        }
        return $value;
    }
}
