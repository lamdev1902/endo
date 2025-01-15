<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class PresentationLayoutDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'appearance' => 'string',
        'display_as' => 'string',
        'max_columns' => 'int',
        'max_slides' => 'int',
        'spacing' => 'int',
    ];

    /**
     * The values that should be constrained after sanitization is run.
     * This is done after $casts and $sanitize.
     */
    public array $enum = [
        'appearance' => ['custom', 'dark', 'light', 'transparent'],
        'display_as' => ['carousel', 'grid', 'list'],
        'max_columns' => [0,2,3,4,5,6],
        'max_slides' => [1,2,3,4,5,6],
        'swiper_options' => ['autoplay', 'navigation', 'pagination'],
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'swiper_options' => 'array-string',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'appearance' => 'light',
            'display_as' => 'grid',
            'max_columns' => 0,
            'max_slides' => 0,
            'spacing' => 16,
            'swiper_options' => [],
        ];
    }

    /**
     * Normalize provided values, this always runs first.
     */
    protected function normalize(array $values = []): array
    {
        if (!isset($values['swiper_options'])) {
            $values['swiper_options'] = ['pagination'];
        }
        return $values;
    }
}
