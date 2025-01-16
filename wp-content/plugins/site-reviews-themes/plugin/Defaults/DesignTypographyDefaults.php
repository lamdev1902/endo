<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class DesignTypographyDefaults extends DefaultsAbstract
{
    public array $casts = [
        'text_large' => 'string',
        'text_normal' => 'string',
        'text_small' => 'string',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'text_large' => '18|27|px|#000000',
            'text_normal' => '14|21|px|#000000',
            'text_small' => '12|18|px|#000000',
        ];
    }
}
