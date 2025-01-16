<?php

namespace GeminiLabs\SiteReviews\Addon\Images;

use GeminiLabs\SiteReviews\Addon\Images\Controllers\Controller;
use GeminiLabs\SiteReviews\Addon\Images\Controllers\ExportController;
use GeminiLabs\SiteReviews\Addon\Images\Controllers\GridController;
use GeminiLabs\SiteReviews\Addon\Images\Controllers\ImportController;
use GeminiLabs\SiteReviews\Addon\Images\Controllers\MediaController;
use GeminiLabs\SiteReviews\Addon\Images\Controllers\RestApiController;
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
            ['addonFiltersBuildAnd', 'site-reviews-filters/sql-and/build/filter_by_media', 10, 3],
            ['addonFiltersBuildJoin', 'site-reviews-filters/sql-join/build/filter_by_media', 10, 3],
            ['allowReviewAttachments', 'wp_ajax_find_posts', 0],
            ['attachImagesToReview', 'site-reviews/review/created', 10, 2],
            ['deleteAttachmentsWithReview', 'before_delete_post'],
            ['enqueueDropzoneInElementor', 'elementor/widget/site_reviews_form/skins_init'],
            ['filterAddonFiltersConfig', 'site-reviews-filters/config/forms/filters-form'],
            ['filterAddonFiltersFilteredBy', 'site-reviews-filters/status/filtered-by', 10, 2],
            ['filterAddonFiltersFilteredCasts', 'site-reviews-filters/defaults/filtered/casts'],
            ['filterAddonFiltersFilteredDefaults', 'site-reviews-filters/defaults/filtered/defaults'],
            ['filterAddonFiltersValidateOrderBy', 'site-reviews-filters/sql-order-by/validate/filter_by_media', 10, 4],
            ['filterDisplayOptions', 'site-reviews/shortcode/display-options', 10, 2],
            ['filterDocumentationShortcodes', 'site-reviews/documentation/shortcodes'],
            ['filterDropzoneTemplate', 'site-reviews/rendered/template/reviews-form', 10, 2],
            ['filterFieldElementDropzone', 'site-reviews/field/element/dropzone'],
            ['filterGuardedCustomFields', 'site-reviews/defaults/custom-fields/guarded'],
            ['filterHideOptions', 'site-reviews/shortcode/hide-options', 10, 2],
            ['filterLocalizedPublicVariables', 'site-reviews/enqueue/public/localize'],
            ['filterQueryReviewsSql', 'site-reviews/database/sql/query-reviews'],
            ['filterReviewDefaultsArray', 'site-reviews/defaults/review/defaults'],
            ['filterReviewFormFields', 'site-reviews/config/forms/review-form', 9],
            ['filterReviewFormOrder', 'site-reviews/review-form/order', 9],
            ['filterReviewImagesTag', 'site-reviews/review/tag/images'],
            ['filterReviewSanitizeArray', 'site-reviews/defaults/review/sanitize'],
            ['filterReviewTemplate', 'site-reviews/build/template/review'],
            ['filterSettingSanitization', 'site-reviews/settings/sanitize', 10, 2],
            ['filterUnguardedPublicActions', 'site-reviews/router/public/unguarded-actions'],
            ['filterValidationType', 'site-reviews/validation/type/images'],
            ['filterValidationStrings', 'site-reviews/defaults/validation-strings/defaults'],
            ['modifyReviewStatus', 'site-reviews/review/created', 10, 2],
            ['removeLegacyWidgets', 'widget_types_to_hide_from_legacy_widget_block'],
            ['renderDropzone', 'wp_footer', 9], // this must load before wp_enqueue_scripts output
            ['renderLightbox', 'wp_footer', 9], // this must load before wp_enqueue_scripts output
            ['renderSwiper', 'wp_footer', 9], // this must load before wp_enqueue_scripts output
            ['routeAjaxRequests', 'site-reviews/route/ajax/'.Application::ID, 10, 2],
        ]));
        $this->hook(ExportController::class, [
            ['filterDatabaseSql', 'site-reviews/database/sql/export-with-ids'],
            ['filterDatabaseSql', 'site-reviews/database/sql/export-with-slugs'],
        ]);
        $this->hook(GridController::class, [
            ['fetchImageGalleryAjax', 'site-reviews/route/ajax/fetch-image-gallery'],
            ['fetchImageReviewAjax', 'site-reviews/route/ajax/fetch-image-review'],
            ['filterLocalizedAdminVariables', 'site-reviews/enqueue/admin/localize'],
            ['filterUnguardedActions', 'site-reviews/router/public/unguarded-actions'],
        ]);
        $this->hook(ImportController::class, [
            ['filterAttachRemoteImages', 'site-reviews/import/reviews/attachments', 10, 3],
            ['filterImportRemoteImages', 'site-reviews/import/review/attachments', 10, 3],
            ['normalizeRequest', 'site-reviews/review/request'],
        ]);
        $this->hook(MediaController::class, [
            ['filterColumnImages', 'site-reviews/column/images'],
            ['filterColumnOrderbyClause', 'posts_clauses', 20, 2],
            ['filterColumnOrderbyDefaults', 'site-reviews/defaults/column-orderby/defaults'],
            ['filterColumnsForPostType', 'manage_'.glsr()->post_type.'_posts_columns', 11],
            ['filterInsertAttachmentData', 'wp_insert_attachment_data'],
            ['filterLocalizedAdminVariables', 'site-reviews/enqueue/admin/localize'],
            ['filterMediaRowActions', 'media_row_actions', 10, 2],
            ['filterPluploadParameters', 'plupload_default_params'],
            ['filterPluploadSettings', 'plupload_default_settings'],
            ['filterQueryAttachmentsArgs', 'ajax_query_attachments_args'],
            ['filterSortableColumns', 'manage_edit-'.glsr()->post_type.'_sortable_columns'],
            ['filterUpdateReviewDefaults', 'site-reviews-authors/defaults/update-review/defaults'],
            ['filterUpdateReviewSanitizers', 'site-reviews-authors/defaults/update-review/sanitize'],
            ['filterUpdateReviewValues', 'site-reviews-authors/update/review-values', 10, 2],
            ['filterUploadDirectory', 'wp_handle_upload_prefilter'],
            ['images', 'site-reviews/review/call/images'],
            ['registerMetaboxes', 'add_meta_boxes_'.glsr()->post_type, 10, 2],
            ['removeAttachmentAdminBarLink', 'admin_bar_menu', 99],
            ['removeAttachmentPermalink', 'edit_form_before_permalink'],
            ['renameAttachment', 'clean_attachment_cache'],
            ['renderTemplates', 'admin_footer-post-new.php'],
            ['renderTemplates', 'admin_footer-post.php'],
            ['saveImagesMetabox', 'site-reviews/review/updated'],
            ['updatedReview', 'site-reviews-authors/review/updated', 10, 2],
        ]);
        $this->hook(RestApiController::class, [
            ['filterReviewsPrepareImages', 'site-reviews/rest-api/reviews/prepare/images', 10, 2],
            ['filterReviewsSchemaProperties', 'site-reviews/rest-api/reviews/schema/properties'],
        ]);
    }
}
