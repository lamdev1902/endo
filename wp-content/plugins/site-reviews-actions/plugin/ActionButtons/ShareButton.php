<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\ActionButtons;

use GeminiLabs\SiteReviews\Addon\Actions\Application;

class ShareButton extends ButtonAbstract
{
    public function iconPath(): string
    {
        return glsr(Application::class)->path('assets/images/icons/share.svg');
    }

    public function text(): string
    {
        return __('Share', 'site-reviews-actions');
    }

    protected function isRestricted(): bool
    {
        return false;
    }
}
