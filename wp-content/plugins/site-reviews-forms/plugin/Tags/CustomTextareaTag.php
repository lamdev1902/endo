<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Text;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class CustomTextareaTag extends Tag
{
    protected function handle(): string
    {
        return $this->wrap($this->value(), 'div');
    }

    protected function listItems(): string
    {
        $text = $this->normalizedValue();
        $values = explode("\n", $text);
        $values = array_filter($values);
        return array_reduce($values, function ($carry, $val) {
            return $carry.glsr(Builder::class)->li(trim($val));
        }, '');
    }

    protected function normalizedValue(): string
    {
        $text = Text::normalize($this->value);
        $text = preg_replace('/( ){1,}/u', ' ', $text);  // replace all multiple space characters with a single space
        $text = wptexturize($text);
        return $text;
    }

    protected function value(): string
    {
        $format = $this->field->get('format', 'excerpt');
        $method = Helper::buildMethodName('valueAs', $format);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
        return $this->value;
    }

    protected function valueAsExcerpt(): string
    {
        $limit = Cast::toInt(glsr_get_option('reviews.excerpts_length', 55));
        return glsr_get_option('reviews.excerpts', false, 'bool')
            ? Text::excerpt($this->value, $limit)
            : Text::text($this->value);
    }

    protected function valueAsOl(): string
    {
        return glsr(Builder::class)->ol($this->listItems());
    }

    protected function valueAsParagraph(): string
    {
        $text = $this->normalizedValue();
        $text = preg_replace('/(\R){1,}/u', PHP_EOL.PHP_EOL, $text);
        $text = wpautop($text); // replace double line breaks with paragraph elements
        return $text;
    }

    protected function valueAsUl(): string
    {
        return glsr(Builder::class)->ul($this->listItems());
    }

    protected function wrapValue(string $tag, string $value): string
    {
        return glsr(Builder::class)->$tag([
            'class' => 'glsr-tag-value',
            'data-expanded' => 'false',
            'text' => $value
        ]);
    }
}
