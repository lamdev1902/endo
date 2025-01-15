<?php

namespace GeminiLabs\SiteReviews\Addon\Filters;

use GeminiLabs\SiteReviews\Modules\Html\ReviewField;
use GeminiLabs\SiteReviews\Modules\Html\Template;

class FilterField extends ReviewField
{
    public function buildFieldDescription(): string
    {
        return '';
    }

    public function buildFieldErrors(): string
    {
        return '';
    }

    public function buildFieldLabel(): string
    {
        return '';
    }

    public function location(): string
    {
        return 'filters';
    }

    public function namePrefix(): string
    {
        return '';
    }
}
