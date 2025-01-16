<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\ActionButtons;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Review;

abstract class ButtonAbstract
{
    public string $action;
    public string $icon;
    public Review $review;
    public string $text;

    public function __construct(Review $review)
    {
        $this->action = $this->action();
        $this->icon = (string) file_get_contents($this->iconPath());
        $this->review = $review;
        $this->text = $this->text();
    }

    public function action(): string
    {
        return Str::dashCase(
            str_replace('Button', 'Review', (new \ReflectionClass($this))->getShortName())
        );
    }

    public function attributes(): array
    {
        return [];
    }

    public function html(): string
    {
        if ($this->isRestricted()) {
            return '';
        }
        return glsr(Builder::class)->button(wp_parse_args($this->attributes(), [
            'class' => sprintf('glsr-%s-button', $this->action),
            'data-action' => $this->action,
            'data-id' => $this->review->ID,
            'text' => "{$this->icon} <span>{$this->text}</span>",
        ]));
    }

    abstract public function iconPath(): string;

    abstract public function text(): string;

    protected function isRestricted(): bool
    {
        if (!glsr(Application::class)->option('hide_restricted', false, 'bool')) {
            return false;
        }
        if ('report' === $this->action) {
            return !glsr(Application::class)->canReportReview();
        }
        if ('upvote' === $this->action) {
            return !glsr(Application::class)->canUpvoteReview();
        }
        return false;
    }
}
