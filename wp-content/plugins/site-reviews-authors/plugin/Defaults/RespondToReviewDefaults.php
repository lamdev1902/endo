<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Defaults;

use GeminiLabs\SiteReviews\Addon\Authors\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class RespondToReviewDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
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
        'response' => 'text-html',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'form_id' => '',
            'response' => '',
            'review_id' => 0,
        ];
    }
}
