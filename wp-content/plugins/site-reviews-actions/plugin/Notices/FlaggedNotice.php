<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Notices;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Notices\AbstractNotice;

class FlaggedNotice extends AbstractNotice
{
    public function render(): void
    {
        glsr(Application::class)->render("notices/flagged");
    }

    protected function canRender(): bool
    {
        if (!$this->isNoticeScreen()) {
            return false;
        }
        if ('post' !== glsr_current_screen()->base) {
            return false;
        }
        if (!glsr_get_review(get_the_ID())->is_flagged) {
            return false;
        }
        return true;
    }

    protected function isDismissible(): bool
    {
        return false;
    }

    protected function isMonitored(): bool
    {
        return false;
    }
}
