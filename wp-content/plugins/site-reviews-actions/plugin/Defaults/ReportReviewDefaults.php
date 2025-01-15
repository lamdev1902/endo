<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Defaults;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class ReportReviewDefaults extends DefaultsAbstract
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
        'email' => 'user-email',
        'form_id' => 'id',
        'message' => 'text',
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
            'email' => '',
            'form_id' => '',
            'message' => '',
            'reason' => '',
            'review_id' => 0,
        ];
    }
}
