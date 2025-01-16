<?php

namespace GeminiLabs\SiteReviews\Addon\Forms;

use GeminiLabs\SiteReviews\Addon\Forms\Controllers\Controller;
use GeminiLabs\SiteReviews\Addon\Forms\Controllers\ExportController;
use GeminiLabs\SiteReviews\Addon\Forms\Controllers\FieldController;
use GeminiLabs\SiteReviews\Addon\Forms\Controllers\ImportController;
use GeminiLabs\SiteReviews\Addon\Forms\Controllers\ListTableController;
use GeminiLabs\SiteReviews\Addon\Forms\Controllers\RestApiController;
use GeminiLabs\SiteReviews\Addon\Forms\Controllers\TemplateController;
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
            ['filterAdminLocalizedVariables', 'site-reviews/enqueue/admin/localize'],
            ['filterBlockAttributes', 'site-reviews-images/block/images/attributes'],
            ['filterBlockAttributes', 'site-reviews/block/form/attributes'],
            ['filterBlockAttributes', 'site-reviews/block/review/attributes'],
            ['filterBlockAttributes', 'site-reviews/block/reviews/attributes'],
            ['filterBlockEditor', 'use_block_editor_for_post_type', 999, 2], // run late
            ['filterDocumentationFaq', 'site-reviews/documentation/faq'],
            ['filterDocumentationShortcodes', 'site-reviews/documentation/shortcodes'],
            ['filterFieldContext', 'site-reviews/interpolate/form/field_assigned_posts'],
            ['filterFieldContext', 'site-reviews/interpolate/form/field_assigned_terms'],
            ['filterFieldContext', 'site-reviews/interpolate/form/field_assigned_users'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_assigned_posts'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_assigned_terms'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_assigned_users'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_checkbox'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_date'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_dropzone'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_email'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_number'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_radio'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_range'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_rating'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_select'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_tel'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_text'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_textarea'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_toggle'],
            ['filterFieldTemplate', 'site-reviews/build/template/form/field_url'],
            ['filterInlinePublicStyles', 'site-reviews/enqueue/public/inline-styles'],
            ['filterLocalizedPublicVariables', 'site-reviews/enqueue/public/localize'],
            ['filterSanitizerFormId', 'site-reviews/sanitizer/form-id'],
            ['filterShortcodeDefaults', 'site-reviews-images/defaults/site-reviews-images/defaults'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-review/defaults'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-reviews-form/defaults'],
            ['filterShortcodeDefaults', 'site-reviews/defaults/site-reviews/defaults'],
            ['filterShortcodeSanitize', 'site-reviews-images/defaults/site-reviews-images/sanitize'],
            ['filterShortcodeSanitize', 'site-reviews/defaults/site-review/sanitize'],
            ['filterShortcodeSanitize', 'site-reviews/defaults/site-reviews-form/sanitize'],
            ['filterShortcodeSanitize', 'site-reviews/defaults/site-reviews/sanitize'],
            ['filterStyleViews', 'site-reviews/style/views'],
            ['metaboxDetailsAjax', 'site-reviews/route/ajax/metabox-details'],
            ['registerMetaBoxes', "add_meta_boxes_{$this->type()}"],
            ['registerPostType', 'init', 8],
            ['renderFields', 'edit_form_after_editor'],
            ['reorderMenu', 'admin_menu', 11],
            ['saveMetaboxes', "save_post_{$this->type()}"],
            ['searchFormsAjax', 'site-reviews/route/ajax/filter-form'],
            ['setOverallRating', 'site-reviews/review/request'],
            ['setOverallRating', 'site-reviews-authors/review/request'],
        ]));
        $this->hook(ExportController::class, [
            ['filterBulkActions', "bulk_actions-edit-{$this->type()}"],
            ['filterRowActions', 'post_row_actions', 10, 2],
            ['onBulkExport', "handle_bulk_actions-edit-{$this->type()}", 10, 3],
            ['onExport', 'admin_action_export'],
        ]);
        $this->hook(FieldController::class, [
            ['filterAdminLocalizedVariables', 'site-reviews/enqueue/admin/localize'],
            ['filterFieldClasses', 'site-reviews/rendered/field/classes', 10, 2],
            ['filterFieldElementAssignedPosts', 'site-reviews/field/element/assigned_posts'],
            ['filterFieldElementAssignedTerms', 'site-reviews/field/element/assigned_terms'],
            ['filterFieldElementAssignedUsers', 'site-reviews/field/element/assigned_users'],
            ['filterHiddenAssignmentFieldValues', 'site-reviews/review-form/fields/all', 10, 2],
            ['filterHiddenFields', 'site-reviews/review-form/fields/hidden', 10, 2],
            ['filterMetaboxFieldsConfig', 'site-reviews/metabox-form/fields', 20, 2],
            ['filterMultiFields', 'site-reviews/review-form/fields/all', 10, 2],
            ['filterReviewCustomDefaults', 'site-reviews/review/build/before', 10, 2],
            ['filterReviewFormConfig', 'site-reviews/review-form/fields', 10, 2],
            ['filterShortcodeAttributes', 'site-reviews/shortcode/args', 10, 2],
            ['filterValidationRules', 'site-reviews/validation/rules', 99, 2],
            ['renderFieldTemplates', 'admin_footer', 1],
            ['setFieldSanitizers', 'site-reviews/defaults', 10, 4],
        ]);
        $this->hook(ImportController::class, [
            ['filterPageHeaderButtons', 'site-reviews/page-header/buttons'],
            ['onImport', "site-reviews/route/admin/import-{$this->type()}"],
            ['registerImporter', 'admin_init'],
        ]);
        $this->hook(ListTableController::class, [
            ['filterColumnFilterby', 'site-reviews/defaults/column-filterby/defaults'],
            ['filterColumnFilterQuery', 'pre_get_posts', 10, 3],
            ['filterColumnsForPostType', "manage_{$this->type()}_posts_columns"],
            ['filterDefaultHiddenColumns', 'default_hidden_columns', 10, 2],
            ['filterListtableFilters', 'site-reviews/defaults/listtable-filters'],
            ['filterRowActions', 'post_row_actions', 100, 2],
            ['filterSearchQuery', 'posts_search', 10, 2],
            ['renderColumnValues', "manage_{$this->type()}_posts_custom_column", 10, 2],
        ]);
        $this->hook(RestApiController::class, [
            ['filterReviewsParameters', 'site-reviews/rest-api/reviews/parameters'],
            ['filterSummaryParameters', 'site-reviews/rest-api/summary/parameters'],
            ['registerRestFields', 'rest_api_init'],
        ]);
        $this->hook(TemplateController::class, [
            ['filterInlineStyles', 'site-reviews/enqueue/public/inline-styles'],
            ['filterReviewTemplate', 'site-reviews/build/template/review', 99, 2],
            ['filterReviewTemplateTags', 'site-reviews/review/build/after', 10, 3],
            ['filterWrappedTagValue', 'site-reviews/custom/wrapped', 10, 3],
            ['filterWrappedTagValue', 'site-reviews/review/wrapped', 10, 3],
        ]);
    }
}
