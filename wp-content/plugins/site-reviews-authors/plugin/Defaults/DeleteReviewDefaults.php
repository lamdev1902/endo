<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Defaults;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class DeleteReviewDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'confirm' => 'bool',
        'review_id' => 'int',
    ];

    /**
     * The values that should be guarded.
     *
     * @var string[]
     */
    public array $guarded = [
        'review_id',
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'form_id' => 'id',
        'reason' => 'text',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'confirm' => false,
            'form_id' => '',
            'reason' => '',
            'review_id' => 0,
        ];
    }
}
