<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Widgets;

use GeminiLabs\SiteReviews\Addon\Forms\Shortcodes\SiteReviewsFieldShortcode;
use GeminiLabs\SiteReviews\Contracts\ShortcodeContract;
use GeminiLabs\SiteReviews\Widgets\SiteReviewsSummaryWidget;

class SiteReviewsFieldWidget extends SiteReviewsSummaryWidget
{
    protected function shortcode(): ShortcodeContract
    {
        return glsr(SiteReviewsFieldShortcode::class);
    }

    protected function widgetDescription(): string
    {
        return _x('Site Reviews: Display a summary of a custom field.', 'admin-text', 'site-reviews-forms');
    }

    protected function widgetName(): string
    {
        return _x('Field Summary', 'admin-text', 'site-reviews-forms');
    }
}
