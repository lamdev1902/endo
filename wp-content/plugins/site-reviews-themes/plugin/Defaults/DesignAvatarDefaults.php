<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class DesignAvatarDefaults extends DefaultsAbstract
{
    public array $casts = [
        'avatar_radius' => 'string',
        'avatar_size' => 'int',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'avatar_radius' => '40|40|40|40|px|1',
            'avatar_size' => 40,
        ];
    }
}
