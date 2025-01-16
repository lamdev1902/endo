<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Controllers;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Addon\Filters\Blocks\SiteReviewsFiltersBlock;
use GeminiLabs\SiteReviews\Addon\Filters\Defaults\FilteredDefaults;
use GeminiLabs\SiteReviews\Addon\Filters\Shortcodes\SiteReviewsFiltersShortcode;
use GeminiLabs\SiteReviews\Addon\Filters\SqlAnd;
use GeminiLabs\SiteReviews\Addon\Filters\SqlJoin;
use GeminiLabs\SiteReviews\Addon\Filters\SqlOrderBy;
use GeminiLabs\SiteReviews\Addon\Filters\Tinymce\SiteReviewsFiltersTinymce;
use GeminiLabs\SiteReviews\Addon\Filters\Widgets\SiteReviewsFiltersWidget;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Reviews;
use GeminiLabs\SiteReviews\Shortcodes\SiteReviewsShortcode;

class Controller extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @action site-reviews/route/ajax/fetch-filtered-reviews
     */
    public function fetchFilteredReviewsAjax(Request $request): void
    {
        glsr()->store(glsr()->paged_handle, $request);
        $html = glsr(SiteReviewsShortcode::class)
            ->normalize($request->cast('atts', 'array'))
            ->buildReviewsHtml();
        $response = [
            'pagination' => $html->getPagination($wrap = true),
            'reviews' => $html->getReviews(),
            'status' => glsr(SiteReviewsFiltersShortcode::class)->buildTemplateTag('status'),
        ];
        wp_send_json_success($response);
    }

    /**
     * @filter site-reviews/enqueue/admin/localize
     */
    public function filterAdminLocalizedVariables(array $variables): array
    {
        $variables = Arr::set($variables, 'hideoptions.site_reviews_filters',
            glsr(SiteReviewsFiltersShortcode::class)->getHideOptions()
        );
        return $variables;
    }

    /**
     * @filter site-reviews-filters/config/forms/filters-form
     */
    public function filterConfigFiltersForm(array $config): array
    {
        $style = glsr_get_option('general.style', 'default');
        $terms = $this->app()->categories(); // @phpstan-ignore-line
        if (!empty($terms)) {
            $config['filter_by_term'] = [
                'options' => ['' => _x('All Categories', 'filter-by option', 'site-reviews-filters')] + $terms,
                'type' => 'select',
                'value' => filter_input(INPUT_GET, 'filter_by_term', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            ];
        }
        if ('divi' === $style) {
            $config['search_for']['class'] = 'search-field et_pb_s';
        }
        return $config;
    }

    /**
     * @filter site-reviews/documentation/shortcodes
     */
    public function filterDocumentationShortcodes(array $sections): array
    {
        $sections['site_reviews_filters'] = $this->app()->path('views/documentation/shortcodes/site_reviews_filters.php');
        return $sections;
    }

    /**
     * @filter site-reviews/enqueue/public/localize
     */
    public function filterLocalizedPublicVariables(array $variables): array
    {
        $variables['addons'][Application::ID] = [
            'allowed' => array_keys(glsr(FilteredDefaults::class)->defaults()),
            'filters' => [],
        ];
        return $variables;
    }

    /**
     * @filter site-reviews/query/sql/and
     */
    public function filterQuerySqlAnd(array $and, string $handle): array
    {
        return in_array($handle, ['query-total-reviews', 'query-review-ids'])
            ? glsr(SqlAnd::class)->modify($and)
            : $and;
    }

    /**
     * @filter site-reviews/query/sql/join
     */
    public function filterQuerySqlJoin(array $join, string $handle): array
    {
        return in_array($handle, ['query-total-reviews', 'query-review-ids'])
            ? glsr(SqlJoin::class)->modify($join)
            : $join;
    }

    /**
     * @filter site-reviews/query/sql/order-by
     */
    public function filterQuerySqlOrderBy(array $orderby, string $handle): array
    {
        return in_array($handle, ['query-review-ids', 'query-reviews'])
            ? glsr(SqlOrderBy::class)->modify($orderby)
            : $orderby;
    }

    /**
     * @filter site-reviews/style/templates
     */
    public function filterStyleTemplates(array $templates): array
    {
        return Arr::unique(array_merge($templates, [
            $this->app()->id.'/templates/reviews-filter',
        ]));
    }

    /**
     * @filter site-reviews/router/public/unguarded-actions
     */
    public function filterUnguardedActions(array $actions): array
    {
        $actions[] = 'fetch-filtered-reviews';
        return $actions;
    }

    /**
     * @action init
     */
    public function registerBlocks(): void
    {
        glsr(SiteReviewsFiltersBlock::class)->register();
    }

    /**
     * @action init
     */
    public function registerShortcodes(): void
    {
        glsr(SiteReviewsFiltersShortcode::class)->register();
    }

    /**
     * @action admin_init
     */
    public function registerTinymcePopups(): void
    {
        glsr(SiteReviewsFiltersTinymce::class)->register();
    }

    /**
     * @action widgets_init
     */
    public function registerWidgets(): void
    {
        register_widget(SiteReviewsFiltersWidget::class);
    }

    /**
     * @param string[] $widgets
     *
     * @filter widget_types_to_hide_from_legacy_widget_block
     */
    public function removeLegacyWidgets(array $widgets): array
    {
        array_push($widgets, 'glsr_site-reviews-filters');
        return $widgets;
    }
}
