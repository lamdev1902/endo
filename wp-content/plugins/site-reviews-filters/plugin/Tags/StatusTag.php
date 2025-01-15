<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Tags;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Addon\Filters\Defaults\FilteredDefaults;
use GeminiLabs\SiteReviews\Addon\Filters\Template;
use GeminiLabs\SiteReviews\Database\NormalizePaginationArgs;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\Tags\Tag;

class StatusTag extends Tag
{
    protected function clearFilterLink(): string
    {
        $paginationArgs = new NormalizePaginationArgs();
        $parameters = array_keys(glsr(FilteredDefaults::class)->defaults());
        return glsr(Builder::class)->a([
            'href' => esc_url(remove_query_arg($parameters, $paginationArgs->pageUrl)),
            'text' => __('Clear filters', 'site-reviews-filters'),
        ]);
    }

    protected function filteredBy(): string
    {
        $filteredBy = [];
        $filters = array_filter(glsr(FilteredDefaults::class)->merge());
        foreach ($filters as $key => $value) {
            if ('filter_by_rating' === $key) {
                $filteredBy[] = $this->filteredByRating($value);
            }
            if ('filter_by_term' === $key && term_exists((int) $value, glsr()->taxonomy)) {
                $filteredBy[] = get_term($value, glsr()->taxonomy)->name;
            }
            if ('search_for' === $key) {
                $filteredBy[] = sprintf(__('Containing %s', 'site-reviews-filters'), '"'.$value.'"');
            }
        }
        $filteredBy = glsr(Application::class)->filterArrayUnique('status/filtered-by', $filteredBy, $filters);
        return implode('; ', $filteredBy);
    }

    protected function filteredByRating($value): string
    {
        $ratingText = [
            'critical' => __('Critical', 'site-reviews-filters'),
            'positive' => __('Positive', 'site-reviews-filters'),
        ];
        if (is_numeric($value)) {
            return sprintf(_n('%s Star', '%s Stars', $value, 'site-reviews-filters'), $value);
        }
        return Arr::get($ratingText, $value);
    }

    protected function handle(): string
    {
        $filters = array_filter(glsr(FilteredDefaults::class)->merge());
        unset($filters['sort_by']); // because technically, this isn't a filter.
        if (empty($filters)) {
            return '';
        }
        $tagname = Str::dashCase($this->tag);
        return glsr(Template::class)->build("templates/{$tagname}", [
            'args' => $this->args,
            'context' => [
                'clear_filters' => $this->clearFilterLink(),
                'filtered_by' => $this->filteredBy(),
                'label' => __('Filtered by', 'site-reviews-filters'),
            ],
        ]);
    }
}
