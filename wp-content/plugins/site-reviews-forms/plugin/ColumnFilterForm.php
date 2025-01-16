<?php

namespace GeminiLabs\SiteReviews\Addon\Forms;

use GeminiLabs\SiteReviews\Controllers\ListTableColumns\AbstractColumnFilter;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;

class ColumnFilterForm extends AbstractColumnFilter
{
    public function label(): string
    {
        return _x('Filter by review form', 'admin-text', 'site-reviews-forms');
    }

    public function options(): array
    {
        return [
            '' => _x('Any review form', 'admin-text', 'site-reviews-forms'),
            0 => _x('No review form', 'admin-text', 'site-reviews-forms'),
        ];
    }

    public function placeholder(): string
    {
        return Arr::get($this->options(), '');
    }

    public function render(): string
    {
        return $this->filterDynamic();
    }

    public function selected(): string
    {
        $value = $this->value();
        if (is_numeric($value) && 0 === Cast::toInt($value)) {
            return Arr::get($this->options(), 0);
        }
        if (!empty($value)) {
            return get_the_title(Cast::toInt($value));
        }
        return $this->placeholder();
    }

    public function value(): string
    {
        return (string) filter_input(INPUT_GET, $this->name(), FILTER_SANITIZE_NUMBER_INT);
    }
}
