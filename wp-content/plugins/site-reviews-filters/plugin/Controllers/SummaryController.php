<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Controllers;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Addon\Filters\Tags\SummaryPercentagesTag;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Contracts\ShortcodeContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Reviews;

class SummaryController extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @filter site-reviews/block/summary/attributes
     */
    public function filterBlockAttributes(array $attributes): array
    {
        $attributes['filters'] = [
            'default' => false,
            'type' => 'boolean',
        ];
        $attributes['reviews_id'] = [
            'default' => '',
            'type' => 'string',
        ];
        return $attributes;
    }

    /**
     * @filter site-reviews/defaults/site-reviews-summary/defaults
     */
    public function filterShortcodeDefaults(array $defaults): array
    {
        $defaults['filters'] = false;
        $defaults['reviews_id'] = '';
        return $defaults;
    }

    /**
     * @filter site-reviews/summary/build/percentages
     */
    public function filterSummaryPercentagesTag(string $field, array $ratings, ShortcodeContract $shortcode): string
    {
        if (!Arr::get($shortcode->args, 'filters')) {
            return $field;
        }
        $args = $shortcode->args;
        $tag = 'percentages';
        $field = glsr(SummaryPercentagesTag::class, compact('tag', 'args'))->handleFor('summary', null, $ratings);
        return $field;
    }
}
