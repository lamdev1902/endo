<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Tags;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewTag;

class ReviewDeleteUrlTag extends ReviewTag
{
    protected function handle(): string
    {
        if ($this->isHidden()) {
            return '';
        }
        return $this->wrap($this->value());
    }

    protected function value(): string
    {
        if (!glsr(Application::class)->canDeleteOnFrontend($this->review)) {
            return '';
        }
        return glsr(Builder::class)->a([
            'data-action' => 'delete-review',
            'data-glsr-trigger' => 'glsr-modal-author',
            'data-id' => $this->review->ID,
            'href' => $this->value,
            'text' => _x('Delete', 'admin-text', 'site-reviews-authors'),
        ]);
    }
}
