<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Tags;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewTag;

class ReviewEditUrlTag extends ReviewTag
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
        if (!glsr(Application::class)->canEditOnFrontend($this->review)) {
            return '';
        }
        glsr()->store('use_dropzone', true);
        return glsr(Builder::class)->a([
            'data-action' => 'update-review',
            'data-glsr-trigger' => 'glsr-modal-author',
            'data-id' => $this->review->ID,
            'href' => $this->value,
            'text' => _x('Edit', 'admin-text', 'site-reviews-authors'),
        ]);
    }
}
