<?php

namespace GeminiLabs\SiteReviews\Addon\Themes;

use GeminiLabs\SiteReviews\Addon\Themes\Controllers\Controller;
use GeminiLabs\SiteReviews\Addon\Themes\Controllers\ExportController;
use GeminiLabs\SiteReviews\Addon\Themes\Controllers\ImportController;
use GeminiLabs\SiteReviews\Addon\Themes\Controllers\ListTableController;
use GeminiLabs\SiteReviews\Addon\Themes\Controllers\RestApiController;
use GeminiLabs\SiteReviews\Addon\Themes\Controllers\ThemeController;
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
            ['filterBlockAttributes', 'site-reviews-images/block/images/attributes'],
            ['filterBlockAttributes', 'site-reviews/block/form/attributes'],
            ['filterBlockAttributes', 'site-reviews/block/review/attributes'],
            ['filterBlockAttributes', 'site-reviews/block/reviews/attributes'],
            ['filterBlockAttributes', 'site-reviews/block/summary/attributes'],
            ['filterBlockEditor', 'use_block_editor_for_post_type', 999, 2], // run late
            ['filterGuardedCustomFields', 'site-reviews/defaults/custom-fields/guarded'],
            ['filterInlineStyles', 'site-reviews/enqueue/public/inline-styles'],
            ['filterLocalizedAdminVariables', 'site-reviews/enqueue/admin/localize'],
            ['filterLocalizedPublicVariables', 'site-reviews/enqueue/public/localize'],
            ['filterSanitizerThemeId', 'site-reviews/sanitizer/theme-id'],
            ['filterShortcodeArgs', 'site-reviews-images/defaults/site-reviews-images', 10, 3],
            ['filterShortcodeArgs', 'site-reviews/defaults/site-review', 10, 3],
            ['filterShortcodeArgs', 'site-reviews/defaults/site-reviews-form', 10, 3],
            ['filterShortcodeArgs', 'site-reviews/defaults/site-reviews-summary', 10, 3],
            ['filterShortcodeArgs', 'site-reviews/defaults/site-reviews', 10, 3],
            ['filterShortcodeDefaults', 'site-reviews-images/defaults/site-reviews-images/defaults'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-review/defaults'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-reviews-form/defaults'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-reviews-summary/defaults'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-reviews/defaults'],
            ['filterShortcodeSanitize', 'site-reviews-images/defaults/site-reviews-images/sanitize'],
            ['filterShortcodeSanitize', 'site-reviews/defaults/site-review/sanitize'],
            ['filterShortcodeSanitize', 'site-reviews/defaults/site-reviews-form/sanitize'],
            ['filterShortcodeSanitize', 'site-reviews/defaults/site-reviews-summary/sanitize'],
            ['filterShortcodeSanitize', 'site-reviews/defaults/site-reviews/sanitize'],
            ['registerMetaBoxes', "add_meta_boxes_{$this->type()}"],
            ['registerPostType', 'init', 8],
            ['renderBetaNotice', 'admin_notices'],
            ['renderNotice', 'edit_form_top'],
            ['renderTemplates', 'admin_footer'],
            ['renderTheme', 'edit_form_after_editor'],
            ['reorderMenu', 'admin_menu'],
            ['themeAjax', 'site-reviews/route/ajax/theme'],
            ['themeTagsAjax', 'site-reviews/route/ajax/theme-tags'],
        ]));
        $this->hook(ExportController::class, [
            ['filterBulkActions', "bulk_actions-edit-{$this->type()}"],
            ['filterRowActions', 'post_row_actions', 10, 2],
            ['onBulkExport', "handle_bulk_actions-edit-{$this->type()}", 10, 3],
            ['onExport', 'admin_action_export'],
        ]);
        $this->hook(ImportController::class, [
            ['filterPageHeaderButtons', 'site-reviews/page-header/buttons'],
            ['onImport', "site-reviews/route/admin/import-{$this->type()}"],
            ['registerImporter', 'admin_init'],
        ]);
        $this->hook(ListTableController::class, [
            ['filterColumnsForPostType', "manage_{$this->type()}_posts_columns"],
            ['filterDefaultHiddenColumns', 'default_hidden_columns', 10, 2],
            ['filterRowActions', 'post_row_actions', 100, 2],
            ['filterSearchQuery', 'posts_search', 10, 2],
            ['renderColumnValues', "manage_{$this->type()}_posts_custom_column", 10, 2],
        ]);
        $this->hook(RestApiController::class, [
            ['filterReviewsParameters', 'site-reviews/rest-api/reviews/parameters'],
            ['filterSummaryParameters', 'site-reviews/rest-api/summary/parameters'],
        ]);
        $this->hook(ThemeController::class, [
            ['filterCustomTagTextarea', 'site-reviews-forms/custom/tag/textarea', 10, 2],
            ['filterFieldElementRating', 'site-reviews/field/element/rating', 10, 2],
            ['filterReviewFormFields', 'site-reviews/review-form/fields', 10, 2],
            ['filterReviewsContext', 'site-reviews/interpolate/reviews', 20, 3],
            ['filterReviewsHtmlTheme', 'site-reviews/reviews/html/theme', 10, 2],
            ['filterReviewsTemplate', 'site-reviews/build/template/reviews', 100, 2], // 1 higher than site-reviews-forms
            ['filterReviewTemplate', 'site-reviews/build/template/review', 100, 2], // 1 higher than site-reviews-forms
            ['filterShortcodeAttributes', 'site-reviews/shortcode/site_review/attributes', 10, 2],
            ['filterShortcodeAttributes', 'site-reviews/shortcode/site_reviews/attributes', 10, 2],
            ['filterShortcodeAttributes', 'site-reviews/shortcode/site_reviews_form/attributes', 10, 2],
            ['filterShortcodeAttributes', 'site-reviews/shortcode/site_reviews_images/attributes', 10, 2],
            ['filterShortcodeAttributes', 'site-reviews/shortcode/site_reviews_summary/attributes', 10, 2],
            ['filterStarRatingPartial', 'site-reviews/partial/classname', 10, 3], // this will override glsr_star_rating output
            ['filterTagAvatar', 'site-reviews/review/tag/avatar', 10, 2],
            ['filterTagContent', 'site-reviews/review/tag/content', 10, 2],
            ['saveTheme', "save_post_{$this->type()}"],
        ]);
    }
}
