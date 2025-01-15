<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\FieldDefaults;

class SettingDefaults extends FieldDefaults
{
    public array $casts = [
        'class' => 'string',
        'label' => 'string',
        'name' => 'string',
        'options' => 'array',
        'required' => 'bool',
        'tag' => 'string',
        'type' => 'string',
        // 'value' => 'string', // disabled because checkbox field value can be an array
    ];

    public array $sanitize = [
        'name' => 'key',
        'tag' => 'key',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'class' => '',
            'label' => '',
            'name' => '',
            'options' => [],
            'type' => '',
            'value' => '',
        ];
    }

    protected function normalize(array $values = []): array
    {
        unset($values['expanded']);
        return $values;
    }
}
