<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\ActionButtons;

use GeminiLabs\SiteReviews\Addon\Actions\Application;

class UpvoteButton extends ButtonAbstract
{
    public function attributes(): array
    {
        return [
            'data-score' => $this->review->score ?: '',
        ];
    }

    public function iconPath(): string
    {
        return glsr(Application::class)->path('assets/images/icons/thumbsup.svg');
    }

    public function text(): string
    {
        return __('Useful', 'site-reviews-actions');
    }
}
