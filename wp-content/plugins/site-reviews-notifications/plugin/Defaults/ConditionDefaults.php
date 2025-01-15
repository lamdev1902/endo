<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications\Defaults;

use GeminiLabs\SiteReviews\Addon\Notifications\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract as Defaults;

class ConditionDefaults extends Defaults
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'field' => '',
            'operator' => '',
            'value' => '',
        ];
    }
}
