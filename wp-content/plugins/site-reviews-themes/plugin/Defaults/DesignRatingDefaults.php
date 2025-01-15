<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class DesignRatingDefaults extends DefaultsAbstract
{
    public array $casts = [
        'rating_colors' => 'string',
        'rating_image' => 'string',
        'rating_size' => 'int',
    ];

    public array $enum = [
        'rating_image' => [
            'default',
            'rating-circle',
            'rating-emoji1',
            'rating-emoji2',
            'rating-heart',
            'rating-heart-circle',
            'rating-paw',
            'rating-paw-circle',
            'rating-star',
            'rating-star-circle',
            'rating-star-mario',
            'rating-star-minecraft',
            'rating-star-rainbow',
            'rating-star-rounded',
            'rating-star-square',
            'rating-star-wordpress',
        ],
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'rating_colors' => '',
            'rating_image' => 'default',
            'rating_size' => 20,
        ];
    }
}
