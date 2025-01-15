<?php

namespace GeminiLabs\SiteReviews\Addon\Actions;

use GeminiLabs\SiteReviews\Addon\Actions\Controllers\Controller;
use GeminiLabs\SiteReviews\Addon\Actions\Controllers\DashboardController;
use GeminiLabs\SiteReviews\Addon\Actions\Controllers\FlaggedController;
use GeminiLabs\SiteReviews\Addon\Actions\Controllers\ReviewFiltersController;
use GeminiLabs\SiteReviews\Addon\Actions\Controllers\ReviewFormsController;
use GeminiLabs\SiteReviews\Addon\Actions\Controllers\TranslateController;
use GeminiLabs\SiteReviews\Addons\Hooks as AddonHooks;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Database;

class Hooks extends AddonHooks
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    public function run(): void
    {
        $this->hook(Controller::class, $this->baseHooks([
            ['fetchReportReviewFormAjax', 'site-reviews/route/ajax/fetch-report-review-form'],
            ['fetchShareReviewFormAjax', 'site-reviews/route/ajax/fetch-share-review-form'],
            ['filterCaptchaActions', 'site-reviews/captcha/actions'],
            ['filterDatabaseTables', 'site-reviews/database/tables'],
            ['filterHideOptions', 'site-reviews/shortcode/hide-options', 10, 2],
            ['filterInlineAdminStyles', 'site-reviews/enqueue/admin/inline-styles'],
            ['filterInlinePublicStyles', 'site-reviews/enqueue/public/inline-styles'],
            ['filterMockActionsTag', 'site-reviews-themes/mock/tag/actions'],
            ['filterReviewTemplate', 'site-reviews/build/template/review', 10, 2],
            ['filterSettingSanitization', 'site-reviews/settings/sanitize', 10, 2],
            ['filterTemplateTag', 'site-reviews/review/tag/actions'],
            ['filterTemplateTags', 'site-reviews/review/build/after', 10, 3],
            ['filterThemeTagDefaults', 'site-reviews-themes/defaults/tag/defaults'],
            ['filterUnguardedActions', 'site-reviews/router/public/unguarded-actions'],
            ['reportReviewAjax', 'site-reviews/route/ajax/report-review'],
            ['shareReviewAjax', 'site-reviews/route/ajax/share-review'],
            ['upvoteReviewAjax', 'site-reviews/route/ajax/upvote-review'],
        ]));
        $this->hook(FlaggedController::class, $this->flaggedHooks());
        $this->hook(ReviewFiltersController::class, [
            ['filterConfigFiltersForm', 'site-reviews-filters/config/forms/filters-form'],
            ['filterQuerySqlOrderBy', 'site-reviews/query/sql/order-by', 20, 2],
        ]);
        $this->hook(ReviewFormsController::class, [
            ['enqueueAdminAssets', 'admin_enqueue_scripts'],
            ['filterFieldDefaultCasts', 'site-reviews-forms/defaults/field/casts'],
            ['modifyTranslatableField', 'site-reviews-forms/field'],
            ['removeSavedTranslation', 'site-reviews-authors/review/updated', 10, 2],
            ['removeSavedTranslation', 'site-reviews/review/updated', 10, 2],
            ['renderTemplates', 'admin_footer', 1],
        ]);
        $this->hook(TranslateController::class, $this->translateHooks());
    }

    protected function flaggedHooks(): array
    {
        if (version_compare(glsr(Database::class)->version(), '1.3', '<')) {
            return [];
        }
        return [
            ['deleteReportAjax', 'site-reviews/route/ajax/delete-report'],
            ['filterDashboardData', 'site-reviews/dashboard/widget/data'],
            ['filterListTableRowClass', 'post_class', 10, 3],
            ['filterListTableViews', "views_edit-{$this->type}"],
            ['filterReviewDefaultsArray', 'site-reviews/defaults/review/defaults'],
            ['filterReviewSanitizeArray', 'site-reviews/defaults/review/sanitize'],
            ['filterReviewTableClauses', 'site-reviews/review-table/clauses'],
            ['registerMetaBoxes', "add_meta_boxes_{$this->type}"],
            ['renderFlaggedNotice', 'admin_head'],
        ];
    }

    protected function translateHooks(): array
    {
        if (!in_array('translate', $this->app()->option('buttons', [], 'array'))) {
            return [];
        }
        $hooks = [];
        if (!empty($this->app()->option('deepl_api_key'))) {
            $hooks = [
                ['changeLanguageAjax', 'site-reviews/route/ajax/change-language'],
                ['filterAdminLocalizedVariables', 'site-reviews/enqueue/admin/localize'],
                ['renderMiscActions', 'post_submitbox_misc_actions'],
                ['translateReviewAjax', 'site-reviews/route/ajax/translate-review'],
            ];
        }
        if (!empty($this->app()->option('detect_language_api_key'))) {
            $hooks[] = ['detectLanguage', 'site-reviews/review/created'];
        }
        return $hooks;
    }
}
