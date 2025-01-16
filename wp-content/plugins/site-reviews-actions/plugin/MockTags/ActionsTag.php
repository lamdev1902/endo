<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\MockTags;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Modules\Html\Tags\Tag;
use GeminiLabs\SiteReviews\Review;

class ActionsTag extends Tag
{
    public function isHidden(string $path = ''): bool
    {
        return false;
    }

    protected function value(): string
    {
        $actions = glsr(Application::class)->option('buttons', [], 'array');
        $buttons = [];
        $review = new Review([], false); // don't init a dummy review
        foreach ($actions as $action) {
            $buttonClass = Helper::buildClassName([$action, 'button'], 'Addon\Actions\ActionButtons');
            if (class_exists($buttonClass)) {
                $buttons[] = (new $buttonClass($review))->html();
            }
        }
        return implode('', $buttons);
    }
}
