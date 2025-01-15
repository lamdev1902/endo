<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Defaults;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class ImageDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'id' => 'int',
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'url' => 'url',
    ];

    protected function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'caption' => '',
            'id' => 0,
            'file' => '',
            'url' => '',
        ];
    }
}
