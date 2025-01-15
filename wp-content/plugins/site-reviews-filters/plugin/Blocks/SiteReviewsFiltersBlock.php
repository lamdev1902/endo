<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Blocks;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Addon\Filters\Shortcodes\SiteReviewsFiltersShortcode;
use GeminiLabs\SiteReviews\Blocks\Block;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class SiteReviewsFiltersBlock extends Block
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    public function attributes(): array
    {
        return [
            'className' => [
                'default' => '',
                'type' => 'string',
            ],
            'hide' => [
                'default' => '',
                'type' => 'string',
            ],
            'id' => [
                'default' => '',
                'type' => 'string',
            ],
            'reviews_id' => [
                'default' => '',
                'type' => 'string',
            ],
        ];
    }

    public function normalizeAttributes(array $attributes): array
    {
        $attributes['class'] = Arr::get($attributes, 'className');
        if ('edit' == filter_input(INPUT_GET, 'context')) {
            $attributes = $this->normalize($attributes);
            if (!$this->hasVisibleFields(glsr(SiteReviewsFiltersShortcode::class), $attributes)) {
                $this->filterInterpolation();
            }
        }
        return $attributes;
    }

    public function render(array $attributes): string
    {
        return glsr(SiteReviewsFiltersShortcode::class)->buildBlock(
            $this->normalizeAttributes($attributes)
        );
    }

    public function renderRaw(array $attributes): string
    {
        $attributes = $this->normalizeAttributes($attributes);
        return glsr(SiteReviewsFiltersShortcode::class)
            ->normalize($attributes, 'block')
            ->buildTemplate();
    }

    protected function filterInterpolation(): void
    {
        add_filter('site-reviews-filters/interpolate/reviews-filter', function ($context) {
            $context['class'] = 'block-editor-warning';
            $context['status'] = glsr(Builder::class)->p([
                'class' => 'block-editor-warning__message',
                'text' => _x('You have hidden all of the fields for this block. However, if you have enabled the filters on the Rating Summary block then the filtered status will display here.', 'admin-text', 'site-reviews-filters'),
            ]);
            return $context;
        });
    }
}
