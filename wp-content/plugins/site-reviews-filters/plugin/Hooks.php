<?php

namespace GeminiLabs\SiteReviews\Addon\Filters;

use GeminiLabs\SiteReviews\Addon\Filters\Controllers\Controller;
use GeminiLabs\SiteReviews\Addon\Filters\Controllers\ReviewsController;
use GeminiLabs\SiteReviews\Addon\Filters\Controllers\SummaryController;
use GeminiLabs\SiteReviews\Addons\Hooks as AddonHooks;
use GeminiLabs\SiteReviews\Contracts\PluginContract;

class Hooks extends AddonHooks
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    public function run(): void
    {
        $this->hook(Controller::class, $this->baseHooks([
            ['fetchFilteredReviewsAjax', 'site-reviews/route/ajax/fetch-filtered-reviews'],
            ['filterAdminLocalizedVariables', 'site-reviews/enqueue/admin/localize'],
            ['filterConfigFiltersForm', 'site-reviews-filters/config/forms/filters-form'],
            ['filterDocumentationShortcodes', 'site-reviews/documentation/shortcodes'],
            ['filterLocalizedPublicVariables', 'site-reviews/enqueue/public/localize'],
            ['filterQuerySqlAnd', 'site-reviews/query/sql/and', 20, 2],
            ['filterQuerySqlJoin', 'site-reviews/query/sql/join', 20, 2],
            ['filterQuerySqlOrderBy', 'site-reviews/query/sql/order-by', 20, 2],
            ['filterStyleTemplates', 'site-reviews/style/templates'],
            ['filterUnguardedActions', 'site-reviews/router/public/unguarded-actions'],
            ['removeLegacyWidgets', 'widget_types_to_hide_from_legacy_widget_block'],
        ]));
        $this->hook(ReviewsController::class, [
            ['filterBlockAttributes', 'site-reviews/block/reviews/attributes'],
            ['filterDisplayOptions', 'site-reviews/shortcode/display-options', 10, 2],
            ['filterShortcodeAttributes', 'site-reviews/shortcode/args', 10, 2],
            ['filterShortcodeCasts', 'site-reviews/defaults/site-reviews/casts'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-reviews/defaults'],
            ['filterTemplate', 'site-reviews/rendered/template/reviews', 10, 2],
            ['highlightSearchResults', 'site-reviews/review/build/before'],
        ]);
        $this->hook(SummaryController::class, [
            ['filterBlockAttributes', 'site-reviews/block/summary/attributes'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-reviews-summary/defaults'],
            ['filterSummaryPercentagesTag', 'site-reviews/summary/build/percentages', 10, 3],
        ]);
    }
}
