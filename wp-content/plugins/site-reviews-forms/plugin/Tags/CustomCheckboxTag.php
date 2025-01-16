<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class CustomCheckboxTag extends Tag
{
    protected function formatWithComma(): string
    {
        $values = explode(',', $this->value);
        return Str::naturalJoin($values);
    }

    protected function formatWithOl(): string
    {
        return glsr(Builder::class)->ol($this->listItems());
    }

    protected function formatWithUl(): string
    {
        return glsr(Builder::class)->ul($this->listItems());
    }

    protected function handle(): string
    {
        return $this->wrap($this->value(), 'div');
    }

    protected function listItems(): string
    {
        $values = explode(',', $this->value);
        return array_reduce($values, function ($carry, $val) {
            return $carry.glsr(Builder::class)->li(trim($val));
        }, '');
    }

    protected function value(): string
    {
        $format = $this->field->get('format', 'ul');
        $method = Helper::buildMethodName('formatWith', $format);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
        return $this->value;
    }
}
