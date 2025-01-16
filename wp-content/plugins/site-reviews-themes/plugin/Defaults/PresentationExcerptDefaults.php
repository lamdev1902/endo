<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class PresentationExcerptDefaults extends DefaultsAbstract
{
    public array $casts = [
        'excerpt_action' => 'string',
        'excerpt_length' => 'string',
    ];

    public array $enum = [
        'excerpt_action' => ['disabled', 'expand', 'modal'],
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'excerpt_action' => 'modal',
            'excerpt_length' => '120|chars',
        ];
    }
}
