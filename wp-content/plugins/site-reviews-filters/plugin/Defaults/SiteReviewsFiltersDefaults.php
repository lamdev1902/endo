<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Defaults;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Addon\Filters\Shortcodes\SiteReviewsFiltersShortcode;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;
use GeminiLabs\SiteReviews\Helpers\Cast;

class SiteReviewsFiltersDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'debug' => 'bool',
    ];

    /**
     * The values that should be guarded.
     * @var string[]
     */
    public array $guarded = [
        'fallback', 'title',
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'class' => 'attr-class',
        'hide' => 'array-string',
        'id' => 'id-hash',
        'reviews_id' => 'id',
        'title' => 'text',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * The default values.
     */
    protected function defaults(): array
    {
        return [
            'class' => '',
            'debug' => false,
            'hide' => [],
            'id' => '',
            'reviews_id' => '',
            'title' => '',
        ];
    }

    /**
     * Normalize provided values, this always runs first.
     */
    protected function normalize(array $args = []): array
    {
        if (empty($args['filters'])) {
            return $args;
        }
        $filters = Cast::toArray($args['filters']);
        $hide = array_keys(glsr(SiteReviewsFiltersShortcode::class)->getHideOptions());
        $args['hide'] = empty(array_intersect($filters, ['1', 'true']))
            ? array_values(array_diff($hide, $filters))
            : [];
        return $args;
    }
}
