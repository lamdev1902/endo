<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Controllers;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Addon\Filters\Blocks\SiteReviewsFiltersBlock;
use GeminiLabs\SiteReviews\Addon\Filters\Defaults\FilteredDefaults;
use GeminiLabs\SiteReviews\Addon\Filters\Defaults\SiteReviewsFiltersDefaults;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Review;
use GeminiLabs\SiteReviews\Reviews;

class ReviewsController extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @filter site-reviews/block/reviews/attributes
     */
    public function filterBlockAttributes(array $attributes): array
    {
        $attributes['filters'] = [
            'default' => '',
            'type' => 'string',
        ];
        return $attributes;
    }

    /**
     * @filter site-reviews/shortcode/display-options
     */
    public function filterDisplayOptions(array $options, string $shortcode): array
    {
        if ('site_reviews' !== $shortcode) {
            return $options;
        }
        return [
            'filter_by_term' => _x('Display the category filter', 'admin-text', 'site-reviews-filters'),
            'filter_by_rating' => _x('Display the rating filter', 'admin-text', 'site-reviews-filters'),
            'search_for' => _x('Display the search bar', 'admin-text', 'site-reviews-filters'),
            'sort_by' => _x('Display the sort by', 'admin-text', 'site-reviews-filters'),
        ];
    }

    /**
     * @filter site-reviews/shortcode/args
     */
    public function filterShortcodeAttributes(array $attributes, string $shortcode): array
    {
        if ('site_reviews' !== $shortcode) {
            return $attributes;
        }
        $parameters = glsr()->args(glsr(FilteredDefaults::class)->merge());
        if ($parameters->filter_by_term) {
            $values = Arr::convertFromString(Arr::get($attributes, 'assigned_terms'));
            $values[] = $parameters->filter_by_term;
            $values = Arr::unique($values);
            $attributes['assigned_terms'] = implode(',', $values);
        }
        return $attributes;
    }

    /**
     * @filter site-reviews/defaults/site-reviews/casts
     */
    public function filterShortcodeCasts(array $casts): array
    {
        $casts['filters'] = 'array';
        return $casts;
    }

    /**
     * @filter site-reviews/defaults/site-reviews/defaults
     */
    public function filterShortcodeDefaults(array $defaults): array
    {
        $defaults['filters'] = [];
        return $defaults;
    }

    /**
     * @filter site-reviews/rendered/template/reviews
     */
    public function filterTemplate(string $template, array $data): string
    {
        if (empty($data['args']->filters)) {
            return $template; // filters are not enabled
        }
        $hideIfNoReviews = $this->app()->filterbool('hide-if-no-reviews', 0 === $data['reviews']->total); // @phpstan-ignore-line
        if (!$this->isUrlFiltered() && $hideIfNoReviews) {
            return $template; // hide the filters when there are no unfiltered reviews to display
        }
        $filters = glsr(SiteReviewsFiltersBlock::class)->renderRaw(
            glsr(SiteReviewsFiltersDefaults::class)->restrict([
                'filters' => $data['args']->filters,
                'reviews_id' => $data['args']->reviews_id, // @phpstan-ignore-line
            ])
        );
        $search = '<div class="glsr-reviews-wrap">';
        if (str_starts_with($template, $search)) {
            return str_replace($search, $search.$filters, $template);
        }
        return $filters.$template;
    }

    /**
     * @action site-reviews/review/build/before
     */
    public function highlightSearchResults(Review $review): void
    {
        $parameters = glsr()->args(glsr(FilteredDefaults::class)->merge());
        if ($search = $parameters->search_for) {
            add_filter('site-reviews/option/reviews/excerpts', '__return_false'); // disable excerpts
            $search = preg_replace('/&#(x)?0*(?(1)27|39);?/i', "'", $search); // decode single quotes
            $search = '/('.preg_quote($search).')/i';
            $replace = '<mark>$1</mark>';
            $review->set('content', preg_replace($search, $replace, $review->content));
            $review->set('title', preg_replace($search, $replace, $review->title));
        }
    }

    protected function isUrlFiltered(): bool
    {
        $filters = array_filter($_GET, function ($key) {
            return Str::startsWith($key, ['filter_', 'search_']);
        }, ARRAY_FILTER_USE_KEY);
        return !empty(array_filter($filters));
    }
}
