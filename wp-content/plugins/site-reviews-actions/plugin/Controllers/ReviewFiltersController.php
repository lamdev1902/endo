<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Controllers;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Addon\Actions\SqlOrderBy;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Controllers\AbstractController;

class ReviewFiltersController extends AbstractController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @filter site-reviews-filters/config/forms/filters-form
     */
    public function filterConfigFiltersForm(array $config): array
    {
        if (isset($config['sort_by']['options'])) {
            $config['sort_by']['options']['useful'] = _x('Most Useful', 'sort by option', 'site-reviews-actions');
            arsort($config['sort_by']['options']);
        }
        return $config;
    }

    /**
     * @filter site-reviews/query/sql/order-by
     */
    public function filterQuerySqlOrderBy(array $orderby, string $handle): array
    {
        if (empty(glsr()->addon('site-reviews-filters'))) {
            return $orderby;
        }
        return in_array($handle, ['query-review-ids', 'query-reviews'])
            ? glsr(SqlOrderBy::class)->modify($orderby)
            : $orderby;
    }
}
