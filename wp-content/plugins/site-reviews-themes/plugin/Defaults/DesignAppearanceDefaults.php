<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class DesignAppearanceDefaults extends DefaultsAbstract
{
    public array $casts = [
        'background_color' => 'string',
        'border_color' => 'string',
        'border_radius' => 'string',
        'border_width' => 'string',
        'padding' => 'string',
        'shadow_1' => 'string',
        'shadow_2' => 'string',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'background_color' => '#ffffff',
            'border_color' => '',
            'border_radius' => '8|8|8|8|px|1',
            'border_width' => '0|0|0|0|px|1',
            'padding' => '16|16|16|16|px|1',
            'shadow_1' => '0|1|3|0|rgba(0,0,0,0.1)',
            'shadow_2' => '0|1|2|0|rgba(0,0,0,0.06)',
        ];
    }
}
