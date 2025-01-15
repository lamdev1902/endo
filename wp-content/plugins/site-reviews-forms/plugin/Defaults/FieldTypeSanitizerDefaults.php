<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Defaults;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class FieldTypeSanitizerDefaults extends DefaultsAbstract
{
    protected function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'checkbox' => 'text',
            'date' => 'date:Y-m-d',
            'email' => 'email',
            'hidden' => 'text',
            'number' => 'numeric',
            'radio' => 'text',
            'range' => 'text',
            'rating' => 'int',
            'select' => 'text',
            'tel' => 'text',
            'text' => 'text',
            'textarea' => 'text-multiline',
            'toggle' => 'text',
            'url' => 'url',
        ];
    }
}
