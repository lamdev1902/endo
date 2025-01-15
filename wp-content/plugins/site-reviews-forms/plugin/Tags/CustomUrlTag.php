<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

class CustomUrlTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'span');
    }

    protected function value(): string
    {
        $format = $this->field->get('format', 'link');
        $value = esc_url($this->value);
        if ('link' === $format) {
            $text = $this->field->get('format_link_text', $value);
            $value = sprintf('<a href="%s">%s</a>', $value, $text);
        } elseif ('link_blank' === $format) {
            $text = $this->field->get('format_link_text', $value);
            $value = sprintf('<a href="%s" target="_blank">%s</a>', $value, $text);
        }
        return $value;
    }
}
