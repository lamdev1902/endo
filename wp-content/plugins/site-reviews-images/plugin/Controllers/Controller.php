<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Controllers;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Addon\Images\Blocks\SiteReviewsImagesBlock;
use GeminiLabs\SiteReviews\Addon\Images\FieldElements\Dropzone;
use GeminiLabs\SiteReviews\Addon\Images\Shortcodes\SiteReviewsImagesShortcode;
use GeminiLabs\SiteReviews\Addon\Images\Tags\ReviewImagesTag;
use GeminiLabs\SiteReviews\Addon\Images\Tinymce\SiteReviewsImagesTinymce;
use GeminiLabs\SiteReviews\Addon\Images\Uploader;
use GeminiLabs\SiteReviews\Addon\Images\Widgets\SiteReviewsImagesWidget;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Commands\CreateReview;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Database\OptionManager;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;

class Controller extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @param int|string $value
     * @param \GeminiLabs\SiteReviews\Addon\Filters\SqlModifier $modifier
     * @action site-reviews-filters/sql-and/build/filter_by_media
     * @todo check for WPML Media
     */
    public function addonFiltersBuildAnd($value, string $key, $modifier): void
    {
        if ('text' === $value) {
            $modifier->values[$key] = 'AND img.ID IS NULL';
        }
    }

    /**
     * @param int|string $value
     * @param \GeminiLabs\SiteReviews\Addon\Filters\SqlModifier $modifier
     * @action site-reviews-filters/sql-join/build/filter_by_media
     * @todo check for WPML Media
     */
    public function addonFiltersBuildJoin($value, string $key, $modifier): void
    {
        if ('images' === $value) {
            $modifier->values[$key] = "INNER JOIN table|posts AS img ON (img.post_parent = r.review_id AND img.post_type = 'attachment')";
        }
        if ('text' === $value) {
            $modifier->values[$key] = "LEFT JOIN table|posts AS img ON (img.post_parent = r.review_id AND img.post_type = 'attachment')";
        }
    }

    /**
     * @action wp_ajax_find_posts
     */
    public function allowReviewAttachments(): void
    {
        global $wp_post_types;
        if (isset($wp_post_types[glsr()->post_type])) {
            $wp_post_types[glsr()->post_type]->public = true;
        }
    }

    /**
     * @action site-reviews/review/created
     */
    public function attachImagesToReview(Review $review, CreateReview $command): void
    {
        if (defined('WP_IMPORTING')) {
            return;
        }
        $images = glsr(Sanitizer::class)->sanitizeJson($command->request->get('images'));
        if (!empty($images)) {
            glsr(Uploader::class)->attachImages($images, $review->ID);
        }
    }

    /**
     * @param int $postId
     * @action before_delete_post
     */
    public function deleteAttachmentsWithReview($postId): void
    {
        if (glsr()->post_type !== get_post_type($postId)) {
            return;
        }
        $attachments = get_attached_media('image', $postId);
        $delete = glsr(Application::class)->option('deletion');
        foreach ($attachments as $attachment) {
            if ('yes' === $delete) {
                wp_delete_attachment($attachment->ID, true);
                continue;
            }
            $attachment->post_title = $attachment->post_name;
            wp_update_post([
                'ID' => $attachment->ID,
                'post_title' => $attachment->post_title,
            ]);
        }
    }

    /**
     * @action admin_enqueue_scripts
     */
    public function enqueueAdminAssets(): void
    {
        if ($this->isReviewAdminPage()) { // only load on the Site Reviews pages!
            wp_enqueue_media(); // just load it all, why not
            $this->enqueueAsset('css', [
                'dependencies' => ['media-views'],
                'suffix' => 'admin',
            ]);
            $this->enqueueAsset('js', [
                'dependencies' => ['backbone', 'jquery-ui-sortable', 'media-grid', 'underscore'],
                'suffix' => 'admin',
            ]);
        }
    }

    /**
     * @action wp_enqueue_scripts
     */
    public function enqueuePublicAssets(): void
    {
        parent::enqueuePublicAssets();
        $scripts = [
            'dropzone' => [
                'src' => 'https://cdn.jsdelivr.net/npm/@deltablot/dropzone@7.1.1/dist/dropzone-min.js',
                'version' => '7.1.1',
            ],
            'exif' => [
                'src' => 'https://cdn.jsdelivr.net/npm/exif-js@2.3/exif.min.js',
                'version' => '2.3',
            ],
            'spotlight' => [
                'src' => 'https://cdn.jsdelivr.net/gh/pryley/spotlight@0.7.8-custom/dist/spotlight.bundle.js',
                'version' => '0.7.8-custom',
            ],
            'splide' => [
                'src' => 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1/dist/js/splide.min.js',
                'version' => '4.1',
            ],
        ];
        if (glsr()->filterBool('assets/use-local', false)) {
            $scripts['dropzone']['src'] = $this->app()->url('assets/npm/dropzone-min.js');
            $scripts['exif']['src'] = $this->app()->url('assets/npm/exif.min.js');
            $scripts['spotlight']['src'] = $this->app()->url('assets/npm/spotlight.bundle.js');
            $scripts['splide']['src'] = $this->app()->url('assets/npm/splide.min.js');
        }
        foreach ($scripts as $handle => $values) {
            wp_register_script(glsr()->id.'/'.$handle, $values['src'], [glsr()->id], $values['version'], true);
        }
    }

    /**
     * @action elementor/widget/site_reviews_form/skins_init
     */
    public function enqueueDropzoneInElementor(): void
    {
        glsr()->store('use_dropzone', true);
    }

    /**
     * @filter site-reviews-filters/config/forms/filters-form
     */
    public function filterAddonFiltersConfig(array $config): array
    {
        $config['filter_by_media'] = [
            'options' => [
                '' => _x('Text, images', 'filter-by option', 'site-reviews-images'),
                'images' => _x('Images only', 'filter-by option', 'site-reviews-images'),
                'text' => _x('Text only', 'filter-by option', 'site-reviews-images'),
            ],
            'type' => 'select',
            'value' => filter_input(INPUT_GET, 'filter_by_media', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ];
        return $config;
    }

    /**
     * @filter site-reviews-filters/status/filtered-by
     */
    public function filterAddonFiltersFilteredBy(array $filteredBy, array $urlParameters): array
    {
        $filter = Arr::get($urlParameters, 'filter_by_media');
        if ('images' === $filter) {
            $filteredBy[] = __('Images only', 'site-reviews-images');
        }
        if ('text' === $filter) {
            $filteredBy[] = __('Text only', 'site-reviews-images');
        }
        return $filteredBy;
    }

    /**
     * @filter site-reviews/defaults/filtered/casts
     */
    public function filterAddonFiltersFilteredCasts(array $casts): array
    {
        $casts['filter_by_media'] = 'string';
        return $casts;
    }

    /**
     * @filter site-reviews/defaults/filtered/defaults
     */
    public function filterAddonFiltersFilteredDefaults(array $defaults): array
    {
        $defaults['filter_by_media'] = '';
        return $defaults;
    }

    /**
     * @param int|string $value
     * @param \GeminiLabs\SiteReviews\Addon\Filters\SqlModifier $modifier
     * @filter site-reviews-filters/sql-order-by/validate/filter_by_media
     */
    public function filterAddonFiltersValidateOrderBy(bool $result, $value, string $parameter, $modifier): bool
    {
        return in_array($value, ['images', 'text']);
    }

    /**
     * @filter site-reviews/shortcode/display-options
     */
    public function filterDisplayOptions(array $options, string $shortcode): array
    {
        if ('site_reviews' === $shortcode) {
            $options['filter_by_media'] = _x('Display the media filter', 'admin-text', 'site-reviews-images');
            natsort($options);
        }
        return $options;
    }

    /**
     * @filter site-reviews/documentation/shortcodes
     */
    public function filterDocumentationShortcodes(array $sections): array
    {
        $sections['site_reviews_images'] = $this->app()->path('views/documentation/site_reviews_images.php');
        return $sections;
    }

    /**
     * @filter site-reviews/rendered/template/reviews-form
     */
    public function filterDropzoneTemplate(string $template, array $data): string
    {
        if (!in_array('images', Arr::consolidate(Arr::get($data, 'args.hide')))) {
            glsr()->store('use_dropzone', true);
        }
        return $template;
    }

    /**
     * @filter site-reviews/field/element/dropzone
     */
    public function filterFieldElementDropzone(): string
    {
        return Dropzone::class;
    }

    /**
     * @filter site-reviews/defaults/custom-fields/guarded
     */
    public function filterGuardedCustomFields(array $guarded): array
    {
        $guarded[] = 'images';
        return Arr::unique($guarded);
    }

    /**
     * @filter site-reviews/shortcode/hide-options
     */
    public function filterHideOptions(array $options, string $shortcode): array
    {
        $insertIndex = array_search('terms', $options);
        if (in_array($shortcode, ['site_review', 'site_reviews'])) {
            return Arr::insertBefore($insertIndex, $options, [
                'images' => esc_html_x('Hide the images', 'admin-text', 'site-reviews-images'),
            ]);
        }
        if ('site_reviews_filters' === $shortcode && glsr('Addon\Filters\Application')->slug) {
            $options = Arr::insertBefore('filter_by_rating', $options, [
                'filter_by_media' => esc_html_x('Hide the images filter', 'admin-text', 'site-reviews-images'),
            ]);
        }
        if ('site_reviews_form' === $shortcode) {
            return Arr::insertBefore($insertIndex, $options, [
                'images' => esc_html_x('Hide the images field', 'admin-text', 'site-reviews-images'),
            ]);
        }
        return $options;
    }

    /**
     * @filter site-reviews/enqueue/public/localize
     */
    public function filterLocalizedPublicVariables(array $variables): array
    {
        $types = glsr(Application::class)->option('mime_types', ['image/jpeg', 'image/png', 'image/webp'], 'array');
        $variables['addons'][Application::ID] = [
            'acceptedfiles' => implode(',', $types),
            'action' => Application::ID,
            'maxfiles' => glsr(Application::class)->option('max_files', 5),
            'maxfilesize' => glsr(Application::class)->option('max_file_size', 5),
            'modal' => glsr(Application::class)->imageModal(),
            'nonce' => wp_create_nonce(Application::ID),
            'swiper' => null,
            'text' => [
                'cancel' => __('Cancel', 'site-reviews-images'),
                'cancelUpload' => __('Cancel upload', 'site-reviews-images'),
                'cancelUploadConfirmation' => __('Are you sure you want to cancel this upload?', 'site-reviews-images'),
                'editCaption' => __('Edit caption', 'site-reviews-images'),
                'enterCaption' => __('Enter a caption', 'site-reviews-images'),
                'fallbackMessage' => __('Your browser does not support drag & drop file uploads.', 'site-reviews-images'),
                'fallbackText' => __('Please use the fallback form below to upload your files like in the olden days.', 'site-reviews-images'),
                'fileTooBig' => __('File is too big ({{filesize}}MB). Max filesize: {{maxFilesize}}MB.', 'site-reviews-images'),
                'genericModalError' => __('Could not load the modal content.', 'site-reviews-images'),
                'imageGallery' => __('Image Gallery', 'site-reviews-images'),
                'invalidFileType' => __('You cannot upload files of this type.', 'site-reviews-images'),
                'maxFilesExceeded' => __('You cannot upload more than {{maxFiles}} images.', 'site-reviews-images'),
                'pleaseWait' => __('Please wait...', 'site-reviews-images'),
                'removeFileConfirmation' => __('Are you sure you want to remove this image?', 'site-reviews-images'),
                'removeImage' => __('Remove image', 'site-reviews-images'),
                'responseError' => __('Server responded with {{statusCode}} code.', 'site-reviews-images'),
                'save' => __('Save', 'site-reviews-images'),
                'uploadCanceled' => __('Upload canceled.', 'site-reviews-images'),
                'viewImageGallery' => __('View Image Gallery', 'site-reviews-images'),
            ],
        ];
        return $variables;
    }

    /**
     * @filter site-reviews/database/sql/query-reviews
     */
    public function filterQueryReviewsSql(string $statement): string
    {
        global $sitepress, $wpdb;
        $languages = defined('ICL_SITEPRESS_VERSION') && is_a($sitepress, '\SitePress')
            ? $sitepress->get_active_languages() // @phpstan-ignore-line
            : [];
        if (count($languages) > 1 && 1 === preg_match('/r\.review_id IN \(([\d, ]+)\)/i', $statement, $matches)) {  // support the WPML plugin
            $statement = Str::replaceFirst('FROM', ', GROUP_CONCAT(DISTINCT img.ID ORDER BY img.menu_order ASC) AS images FROM', $statement);
            $statement = Str::replaceFirst('WHERE', "
                LEFT JOIN {$wpdb->posts} AS img ON (img.post_parent = r.review_id AND img.ID IN (
                    SELECT MIN(icl.element_id)
                    FROM {$wpdb->prefix}icl_translations AS icl
                    INNER JOIN {$wpdb->posts} AS i ON (i.ID = icl.element_id AND icl.element_type = 'post_attachment' AND i.post_parent IN ({$matches[1]}))
                    GROUP BY icl.trid
                ))
                WHERE
            ", $statement);
            return $statement;
        }
        $statement = Str::replaceFirst('FROM', ', GROUP_CONCAT(DISTINCT img.ID ORDER BY img.menu_order ASC) AS images FROM', $statement);
        $statement = Str::replaceFirst('WHERE', "LEFT JOIN {$wpdb->posts} AS img ON (img.post_parent = r.review_id AND img.post_type = 'attachment') WHERE", $statement);
        return $statement;
    }

    /**
     * @filter site-reviews/defaults/review/defaults
     */
    public function filterReviewDefaultsArray(array $defaults): array
    {
        $defaults['images'] = '';
        return $defaults;
    }

    /**
     * @filter site-reviews/review/tag/images
     */
    public function filterReviewImagesTag(): string
    {
        return ReviewImagesTag::class;
    }

    /**
     * @filter site-reviews/defaults/review/sanitize
     */
    public function filterReviewSanitizeArray(array $sanitize): array
    {
        $sanitize['images'] = 'array-int';
        return $sanitize;
    }

    /**
     * @filter site-reviews/build/template/review
     */
    public function filterReviewTemplate(string $template): string
    {
        if (true === glsr()->retrieve('image_review')) {
            return $template;
        }
        if (!str_contains($template, '{{ images }}')) {
            $template = str_replace('{{ content }}', '{{ content }} {{ images }}', $template);
        }
        return $template;
    }

    /**
     * @filter site-reviews/defer-scripts
     */
    public function filterScriptsDefer(array $handles): array
    {
        $handles[] = glsr()->id.'/dropzone';
        $handles[] = glsr()->id.'/exif';
        $handles[] = glsr()->id.'/splide';
        $handles[] = glsr()->id.'/spotlight';
        return $handles;
    }

    /**
     * @filter site-reviews/addon/settings
     */
    public function filterSettings(array $settings): array
    {
        $options = glsr(OptionManager::class)->wp(OptionManager::databaseKey(), [], 'array');
        $settings = parent::filterSettings($settings);
        $settings['settings.forms.required']['options']['images'] = esc_attr_x('Images', 'admin-text', 'site-reviews-images');
        if ('no' === Arr::get($options, 'settings.addons.images.require_approval')) {
            return $settings;
        }
        $description = sprintf(_x('The %sRequire Image Approval%s option may override this setting.', '<a> link tags (admin-text)', 'site-reviews-images'),
            sprintf('<a href="%s">', glsr_admin_url('settings', 'addons', 'images')), '</a>'
        );
        if ('no' === Arr::get($options, 'settings.general.require.approval')) {
            $settings['settings.general.require.approval']['description'] = $description;
        } else {
            $settings['settings.general.require.approval_for']['description'] = $description;
        }
        return $settings;
    }

    /**
     * @filter site-reviews/settings/sanitize
     */
    public function filterSettingSanitization(array $options, array $input): array
    {
        $key = 'settings.addons.images.mime_types';
        $mimeTypes = Arr::get($input, $key, []);
        $options = Arr::set($options, $key, $mimeTypes);
        return $options;
    }

    /**
     * @filter site-reviews/config/forms/review-form
     */
    public function filterReviewFormFields(array $fields): array
    {
        $fields['images'] = [
            'label' => esc_html__('Do you have photos to share?', 'site-reviews-images'),
            'type' => 'dropzone',
        ];
        return $fields;
    }

    /**
     * @filter site-reviews/review-form/order
     */
    public function filterReviewFormOrder(array $order): array
    {
        return Arr::insertBefore(array_search('terms', $order), $order, ['images']);
    }

    /**
     * @filter site-reviews/router/public/unguarded-actions
     */
    public function filterUnguardedPublicActions(array $actions): array
    {
        $actions[] = Application::ID;
        return $actions;
    }

    /**
     * @filter site-reviews/validation/type/images
     */
    public function filterValidationType(string $type): string
    {
        return 'images';
    }

    /**
     * @filter site-reviews/validation/type/images
     */
    public function filterValidationStrings(array $defaults): array
    {
        $defaults['maximages'] = __('This field allows a maximum of %s images.', 'site-reviews-images');
        $defaults['minimages'] = __('This field requires a minimum of %s images.', 'site-reviews-images');
        return $defaults;
    }

    /**
     * @action site-reviews/review/created
     */
    public function modifyReviewStatus(Review $review, CreateReview $command): void
    {
        $images = glsr(Sanitizer::class)->sanitizeJson($command->request->get('images'));
        if (empty($images)) {
            return;
        }
        if ($this->app()->option('require_approval', 'no', 'bool')) {
            glsr_log('images: mark review as unapproved');
            wp_update_post([
                'ID' => $review->ID,
                'post_status' => 'pending',
            ]);
        }
    }

    /**
     * @action init
     */
    public function registerBlocks(): void
    {
        glsr(SiteReviewsImagesBlock::class)->register();
    }

    /**
     * @action init
     */
    public function registerShortcodes(): void
    {
        glsr(SiteReviewsImagesShortcode::class)->register();
    }

    /**
     * @action admin_init
     */
    public function registerTinymcePopups(): void
    {
        glsr(SiteReviewsImagesTinymce::class)->register();
    }

    /**
     * @action widgets_init
     */
    public function registerWidgets(): void
    {
        register_widget(SiteReviewsImagesWidget::class);
    }

    /**
     * @param string[] $widgets
     *
     * @filter widget_types_to_hide_from_legacy_widget_block
     */
    public function removeLegacyWidgets(array $widgets): array
    {
        array_push($widgets, 'glsr_site-reviews-images');
        return $widgets;
    }

    /**
     * @action wp_footer
     */
    public function renderDropzone(): void
    {
        if (Cast::toBool(glsr()->retrieve('use_dropzone'))) {
            wp_enqueue_script(glsr()->id.'/dropzone');
            wp_enqueue_script(glsr()->id.'/exif');
        }
    }

    /**
     * @action wp_footer
     */
    public function renderLightbox(): void
    {
        if (Cast::toBool(glsr()->retrieve('use_images'))) { // this tells us that images have been loaded on the page
            if ('lightbox' === glsr(Application::class)->imageModal()) {
                wp_enqueue_script(glsr()->id.'/spotlight');
            }
        }
    }

    /**
     * @action wp_footer
     */
    public function renderSwiper(): void
    {
        if (Cast::toBool(glsr()->retrieve('use_swiper'))) { // [site_reviews_images] has been loaded on the page
            wp_enqueue_script(glsr()->id.'/splide');
        }
    }

    /**
     * @action site-reviews/route/ajax/{Application::ID}
     */
    public function routeAjaxRequests(Request $request): void
    {
        $result = glsr(Uploader::class)->handle($request);
        if (is_wp_error($result)) {
            wp_send_json_error(['error' => $result->get_error_message()]);
        }
        wp_send_json_success($result);
    }
}
