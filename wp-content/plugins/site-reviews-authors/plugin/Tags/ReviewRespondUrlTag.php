<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Tags;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewTag;

class ReviewRespondUrlTag extends ReviewTag
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
        if (!glsr(Application::class)->canRespondOnFrontend($this->review)) {
            return '';
        }
        return glsr(Builder::class)->a([
            'data-action' => 'respond-to-review',
            'data-glsr-trigger' => 'glsr-modal-author',
            'data-id' => $this->review->ID,
            'href' => $this->value,
            'text' => _x('Respond', 'admin-text', 'site-reviews-authors'),
        ]);
    }
}
