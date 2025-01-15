<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications\Defaults;

use GeminiLabs\SiteReviews\Addon\Notifications\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract as Defaults;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Str;

class NotificationDefaults extends Defaults
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'enabled' => 'bool',
        'recipients' => 'array',
        'schedule' => 'int',
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'conditions' => 'text',
        'heading' => 'text-html',
        'message' => 'text-post',
        'subject' => 'text',
        'uid' => 'text',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'conditions' => 'always',
            'enabled' => false,
            'heading' => '',
            'message' => '',
            'recipients' => [],
            'schedule' => 0,
            'subject' => '',
            'uid' => '',
        ];
    }

    /**
     * Finalize provided values, this always runs last.
     */
    protected function finalize(array $values = []): array
    {
        return wp_unslash($values); // unslash data!
    }
}
