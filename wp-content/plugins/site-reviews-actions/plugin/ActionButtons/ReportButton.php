<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\ActionButtons;

use GeminiLabs\SiteReviews\Addon\Actions\Application;

class ReportButton extends ButtonAbstract
{
    public function iconPath(): string
    {
        return glsr(Application::class)->path('assets/images/icons/flag.svg');
    }

    public function text(): string
    {
        return __('Report', 'site-reviews-actions');
    }
}
