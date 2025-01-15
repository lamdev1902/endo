<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Controllers;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Addon\Images\Attachment;
use GeminiLabs\SiteReviews\Addon\Images\Columns\ColumnValueImages;
use GeminiLabs\SiteReviews\Addon\Images\Uploader;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Sanitizers\SanitizeJson;
use GeminiLabs\SiteReviews\Review;

class MediaController extends AbstractController
{
    /**
     * @filter site-reviews/column/images
     */
    public function filterColumnImages(): string
    {
        return ColumnValueImages::class;
    }

    /**
     * @filter posts_clauses
     */
    public function filterColumnOrderbyClause(array $clauses, \WP_Query $query): array
    {
        if (!$this->hasQueryPermission($query) || 'images' !== $query->get('orderby')) {
            return $clauses;
        }
        global $sitepress, $wpdb;
        $join = '';
        $order = $query->get('order');
        $clauses['groupby'] = "{$wpdb->posts}.ID";
        $clauses['orderby'] = "COUNT(DISTINCT img.ID) $order, {$wpdb->posts}.post_date DESC";
        $languages = defined('ICL_SITEPRESS_VERSION') && is_a($sitepress, '\SitePress')
            ? $sitepress->get_active_languages() // @phpstan-ignore-line
            : [];
        if (2 > count($languages)) { // support the WPML plugin?
            $clauses['join'] .= " LEFT JOIN {$wpdb->posts} AS img ON (img.post_parent = {$wpdb->posts}.ID AND img.post_type = 'attachment') ";
            return $clauses;
        }
        $clauses['join'] .= "
            LEFT JOIN {$wpdb->posts} AS img ON (img.post_parent = {$wpdb->posts}.ID AND img.post_type = 'attachment' AND img.ID IN (
              SELECT MIN(icl.element_id)
              FROM {$wpdb->prefix}icl_translations AS icl
              INNER JOIN {$wpdb->posts} AS i ON (i.ID = icl.element_id AND icl.element_type = 'post_attachment' AND i.post_parent IN (
                SELECT {$wpdb->posts}.ID
                FROM {$wpdb->posts}
                WHERE 1=1 
                {$clauses['where']}
                GROUP BY {$wpdb->posts}.ID
              ))
              GROUP BY icl.trid
            ))
        ";
        return $clauses;
    }

    /**
     * @filter site-reviews/defaults/column-orderby/defaults
     */
    public function filterColumnOrderbyDefaults(array $defaults): array
    {
        $defaults['images'] = 'images';
        return $defaults;
    }

    /**
     * @param array $columns
     * @filter manage_{glsr()->post_type}_posts_columns
     */
    public function filterColumnsForPostType($columns): array
    {
        $columns = Arr::consolidate($columns);
        $label = '<span class="images-icon"><span>'.esc_html_x('Images', 'admin-text', 'site-reviews-images').'</span></span>';
        $columns = Arr::insertBefore('is_pinned', $columns, [
            'images' => $label,
        ]);
        return $columns;
    }

    /**
     * @param array $data
     * @filter wp_insert_attachment_data
     */
    public function filterInsertAttachmentData($data): array
    {
        $data = Arr::consolidate($data);
        if (Application::ID === Helper::filterInput(Application::ID)) {
            $title = sprintf(esc_attr_x('%s Image', 'image title', 'site-reviews-images'), glsr()->name);
            $data['post_status'] = 'private';
            $data['post_title'] = sanitize_text_field($title);
        }
        return $data;
    }

    /**
     * @filter site-reviews/enqueue/admin/localize
     */
    public function filterLocalizedAdminVariables(array $variables): array
    {
        $variables['addons'][Application::ID] = [
            'text' => [
                'add' => _x('Add Images', 'media (admin-text)', 'site-reviews-images'),
                'edit' => _x('Edit Image', 'media (admin-text)', 'site-reviews-images'),
                'swap' => _x('Swap Image', 'media (admin-text)', 'site-reviews-images'),
                'extensions' => [
                    'image/jpeg' => ['jpg', 'jpeg'],
                    'image/png' => ['png'],
                    'image/webp' => ['webp'],
                ],
                'loadingUrl' => admin_url('images/spinner.gif'),
                'multiple' => _x(' images', 'media (admin-text)', 'site-reviews-images'),
                'noTitle' => _x('No Title', 'media (admin-text)', 'site-reviews-images'),
                'or' => _x('or', 'media (admin-text)', 'site-reviews-images'),
                'remove' => _x('Remove Image', 'media (admin-text)', 'site-reviews-images'),
                'select' => _x('Select Images', 'media (admin-text)', 'site-reviews-images'),
                'single' => _x(' file', 'media (admin-text)', 'site-reviews-images'),
                'uploadInstructions' => _x('Drop files here to upload', 'media (admin-text)', 'site-reviews-images'),
                'view' => _x('View', 'media (admin-text)', 'site-reviews-images'),
            ],
        ];
        return $variables;
    }

    /**
     * @param array $actions
     * @param \WP_Post $post
     * @filter media_row_actions
     */
    public function filterMediaRowActions($actions, $post): array
    {
        $actions = Arr::consolidate($actions);
        if (!is_admin() || !is_a($post, 'WP_Post')) {
            return $actions;
        }
        if (!empty($post->post_parent) && $parent = get_post($post->post_parent)) {
            if ($parent->post_type == glsr()->post_type) {
                $actions['view'] = '';
            }
        }
        return $actions;
    }

    /**
     * @param array $parameters
     * @filter plupload_default_params
     */
    public function filterPluploadParameters($parameters): array
    {
        $parameters = Arr::consolidate($parameters);
        $screen = glsr_current_screen();
        if ('post' === $screen->base && $screen->post_type === glsr()->post_type) {
            $parameters[Application::ID] = Application::ID;
        }
        return $parameters;
    }

    /**
     * @param array $settings
     * @filter plupload_default_settings
     */
    public function filterPluploadSettings($settings): array
    {
        $screen = glsr_current_screen();
        $settings = Arr::consolidate($settings);
        if ('post' === $screen->base && $screen->post_type === glsr()->post_type) {
            $settings = Arr::set($settings, 'filters.mime_types', [[
                'extensions' => 'jpg,jpeg,png,webp',
            ]]);
        }
        return $settings;
    }

    /**
     * @param array $query
     * @filter ajax_query_attachments_args
     */
    public function filterQueryAttachmentsArgs($query): array
    {
        $query = Arr::consolidate($query);
        if (Application::ID === Arr::get($query, 's')) {
            unset($query['s']);
            remove_filter('posts_clauses', '_filter_query_attachment_filenames'); // replace the search query with our own
            add_filter('posts_clauses', [$this, 'filterQueryAttachmentsPostClauses']);
        }
        return $query;
    }

    /**
     * @param array $clauses
     * @filter posts_clauses
     * @see filterQueryAttachmentsArgs
     */
    public function filterQueryAttachmentsPostClauses($clauses): array
    {
        global $wpdb;
        $clauses = Arr::consolidate($clauses);
        remove_filter('posts_clauses', [$this, 'filterQueryAttachmentsPostClauses']);
        $clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} AS glsri ON (glsri.post_id = {$wpdb->posts}.ID AND glsri.meta_key = '_wp_attached_file')";
        $clauses['where'] .= " AND (glsri.meta_value LIKE 'site-reviews/%')";
        return $clauses;
    }

    /**
     * @param array $columns
     * @filter manage_edit-{glsr()->post_type}_sortable_columns
     */
    public function filterSortableColumns($columns): array
    {
        $columns = Arr::consolidate($columns);
        $columns = Arr::consolidate($columns);
        $columns['images'] = 'images';
        return $columns;
    }

    /**
     * @filter site-reviews-authors/defaults/update-review/defaults
     */
    public function filterUpdateReviewDefaults(array $defaults): array
    {
        $defaults['images'] = [];
        return $defaults;
    }

    /**
     * @filter site-reviews-authors/defaults/update-review/sanitize
     */
    public function filterUpdateReviewSanitizers(array $sanitizers): array
    {
        $sanitizers['images'] = 'array-consolidate';
        return $sanitizers;
    }

    /**
     * @param \GeminiLabs\SiteReviews\Addon\Authors\Commands\UpdateReview $command
     * @filter site-reviews-authors/update/review-values
     */
    public function filterUpdateReviewValues(array $values, $command): array
    {
        $values['images'] = $command->request->images; // @phpstan-ignore-line
        return $values;
    }

    /**
     * @param array $file
     * @filter wp_handle_upload_prefilter
     */
    public function filterUploadDirectory($file): array
    {
        $file = Arr::consolidate($file);
        if (Application::ID === Helper::filterInput(Application::ID)) {
            glsr(Uploader::class)->setUploadDirectory(glsr()->id);
        }
        return $file;
    }

    /**
     * @filter site-reviews/review/call/images
     */
    public function images(Review $review): array
    {
        $images = [];
        foreach ($review->images as $imageId) {
            $medium = wp_get_attachment_image_src($imageId, 'medium');
            $large = wp_get_attachment_image_src($imageId, 'large');
            $images[] = glsr()->args([
                'ID' => $imageId,
                'caption' => wp_get_attachment_caption($imageId),
                'src' => $medium[0],
                'width' => $medium[1],
                'height' => $medium[2],
                'large_src' => $large[0],
                'large_width' => $large[1],
                'large_height' => $large[2],
            ]);
        }
        return $images;
    }

    /**
     * @action add_meta_boxes
     */
    public function registerMetaboxes(\WP_Post $post): void
    {
        add_meta_box(Application::ID, _x('Images', 'admin-text', 'site-reviews-images'), [$this, 'renderImagesMetabox'], null, 'normal');
    }

    /**
     * @action admin_bar_menu
     */
    public function removeAttachmentAdminBarLink(\WP_Admin_Bar $adminBar): void
    {
        if (!is_admin()) {
            return;
        }
        $screen = glsr_current_screen();
        if ('post' != $screen->base || 'attachment' != $screen->post_type) {
            return;
        }
        $parent = get_post(get_post()->post_parent);
        if ($parent->post_type === glsr()->post_type) {
            $adminBar->remove_node('view');
        }
    }

    /**
     * @action edit_form_before_permalink
     */
    public function removeAttachmentPermalink(\WP_Post $post): void
    {
        if (!is_admin()) {
            return;
        }
        if ('attachment' != $post->post_type || empty($post->post_parent)) {
            return;
        }
        $parent = get_post($post->post_parent);
        if ($parent->post_type === glsr()->post_type) {
            global $post_type_object;
            $post_type_object->public = false;
        }
    }

    /**
     * @param int $attachmentId
     * @action clean_attachment_cache
     */
    public function renameAttachment($attachmentId): void
    {
        $attachment = get_post($attachmentId);
        $newParentId = Cast::toInt(filter_input(INPUT_GET, 'found_post_id'));
        $oldParentId = Cast::toInt(filter_input(INPUT_GET, 'parent_post_id'));
        if (!empty($oldParentId)) {
            if (glsr()->post_type === get_post_type($oldParentId)) {
                $attachment->post_title = $attachment->post_name;
                wp_update_post([
                    'ID' => $attachment->ID,
                    'post_title' => $attachment->post_title,
                ]);
            }
        } elseif (!empty($newParentId)) {
            if (glsr()->post_type === get_post_type($newParentId)) {
                $attachment->post_title = sprintf(esc_attr_x('%s Image', 'image title', 'site-reviews-images'), glsr()->name);
                wp_update_post([
                    'ID' => $attachment->ID,
                    'post_title' => $attachment->post_title,
                ]);
            }
        }
    }

    /**
     * @callback add_meta_box
     */
    public function renderImagesMetabox(\WP_Post $post): void
    {
        if ($post->post_type === glsr()->post_type) {
            $attachments = $this->getAttachmentsForJs($post->ID);
            glsr(Application::class)->render('views/metabox-images', [
                'addon' => Application::ID,
                'input' => glsr(Builder::class)->input([
                    'class' => 'glsri-media',
                    'data-attachments' => json_encode(array_values($attachments)),
                    'name' => glsr(Application::class)->ID,
                    'type' => 'hidden',
                    'value' => implode(',', glsr_get_review($post->ID)->images),
                ]),
            ]);
        }
    }

    /**
     * @action admin_footer-post.php
     */
    public function renderTemplates(): void
    {
        if (get_post_type() === glsr()->post_type) {
            glsr(Application::class)->render('views/templates', [
                'addon' => Application::ID,
            ]);
        }
    }

    /**
     * @action site-reviews/review/updated
     */
    public function saveImagesMetabox(Review $review): void
    {
        if ('post' !== glsr_current_screen()->base) {
            return; // only run this on the edit review page.
        }
        if ($imageIds = Helper::filterInputArray(Application::ID)) {
            $attachIds = array_diff($imageIds, $review->images);
            $detachIds = array_diff($review->images, array_diff($imageIds, $attachIds));
            glsr(Attachment::class)->attach($attachIds, $review->ID);
            glsr(Attachment::class)->detach($detachIds, $review->ID);
            glsr(Attachment::class)->normalize($imageIds); // fix status, slug, and menu order
        } elseif (!empty($review->images)) {
            glsr(Attachment::class)->detach($review->images, $review->ID);
        }
    }

    /**
     * @param \GeminiLabs\SiteReviews\Addon\Authors\Commands\UpdateReview $command
     * @action site-reviews-authors/review/updated
     */
    public function updatedReview(Review $review, $command): void
    {
        $images = (new SanitizeJson($command->request->get('images')))->run(); // @phpstan-ignore-line
        if (!empty($images)) {
            glsr(Uploader::class)->attachImages($images, $review->ID);
        }
    }

    protected function getAttachmentsForJs(int $postId): array
    {
        $attachments = [];
        foreach (glsr_get_review($postId)->images as $imageId) {
            $attachment = wp_prepare_attachment_for_js($imageId);
            if (!empty($attachment)) {
                unset($attachment['compat']); // Some plugins/themes add HTML to the "compat" attrbute which breaks the JSON
                $attachments[] = $attachment;
            }
        }
        return $attachments;
    }
}
