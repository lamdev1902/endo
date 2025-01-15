<?php

namespace GeminiLabs\SiteReviews\Addon\Actions;

use GeminiLabs\SiteReviews\Addons\Addon;

final class Application extends Addon
{
    public const ID = 'site-reviews-actions';
    public const LICENSED = true;
    public const NAME = 'Review Actions';
    public const SLUG = 'actions';

    public function canReportReview(): bool
    {
        if (is_user_logged_in()) {
            return true;
        }
        if ('user' !== $this->option('report_restricted')) {
            return true;
        }
        return false;
    }

    public function canUpvoteReview(): bool
    {
        if (is_user_logged_in()) {
            return true;
        }
        if ('user' !== $this->option('upvote_restricted')) {
            return true;
        }
        return false;
    }
}
