<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Blocks;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Addon\Images\Shortcodes\SiteReviewsImagesShortcode;
use GeminiLabs\SiteReviews\Blocks\Block;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class SiteReviewsImagesBlock extends Block
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    public function attributes(): array
    {
        return [
            'assigned_post' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_posts' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_term' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_terms' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_user' => [
                'default' => '',
                'type' => 'string',
            ],
            'assigned_users' => [
                'default' => '',
                'type' => 'string',
            ],
            'className' => [
                'default' => '',
                'type' => 'string',
            ],
            'display' => [
                'default' => 8,
                'type' => 'number',
            ],
            'hide' => [
                'default' => '',
                'type' => 'string',
            ],
            'id' => [
                'default' => '',
                'type' => 'string',
            ],
            'rating' => [
                'default' => 0,
                'type' => 'number',
            ],
            'terms' => [
                'default' => '',
                'type' => 'string',
            ],
            'type' => [
                'default' => 'local',
                'type' => 'string',
            ],
        ];
    }

    public function normalizeAttributes(array $attributes): array
    {
        $attributes['class'] = Arr::get($attributes, 'className');
        if ('edit' == filter_input(INPUT_GET, 'context')) {
            $attributes = $this->normalize($attributes);
            $this->filterInterpolation();
        }
        return $attributes;
    }

    public function render(array $attributes): string
    {
        return glsr(SiteReviewsImagesShortcode::class)->buildBlock(
            $this->normalizeAttributes($attributes)
        );
    }

    public function renderRaw(array $attributes): string
    {
        $attributes = $this->normalizeAttributes($attributes);
        return glsr(SiteReviewsImagesShortcode::class)
            ->normalize($attributes, 'block')
            ->buildTemplate();
    }

    protected function filterInterpolation(): void
    {
        add_filter('site-reviews-images/interpolate/review-images', function ($context, $template, $data) {
            if (empty($data['results']['total'])) {
                $context['class'] = 'block-editor-warning';
                $context['link'] = glsr(Builder::class)->p([
                    'class' => 'block-editor-warning__message',
                    'text' => _x('No review images were found.', 'admin-text', 'site-reviews-images'),
                ]);
            }
            return $context;
        }, 10, 3);
    }
}
