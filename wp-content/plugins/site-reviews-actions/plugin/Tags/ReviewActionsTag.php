<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Tags;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewTag;

class ReviewActionsTag extends ReviewTag
{
    protected function handle(): string
    {
        if ($this->isHidden('addons.actions.buttons')) {
            return '';
        }
        return $this->wrap($this->value());
    }

    public function isEnabled(string $path): bool
    {
        if ($this->isRaw() || glsr()->retrieveAs('bool', 'api', false)) {
            return true;
        }
        return !empty(glsr_get_option($path, [], 'array'));
    }

    protected function value(): string
    {
        $actions = glsr(Application::class)->option('buttons', [], 'array');
        $buttons = [];
        foreach ($actions as $action) {
            $buttonClass = Helper::buildClassName([$action, 'button'], 'Addon\Actions\ActionButtons');
            if (class_exists($buttonClass)) {
                $buttons[] = (new $buttonClass($this->review))->html();
            }
        }
        return implode('', $buttons);
    }
}
