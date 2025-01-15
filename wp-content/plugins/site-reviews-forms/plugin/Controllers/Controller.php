<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Controllers;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\Blocks\SiteReviewsFieldBlock;
use GeminiLabs\SiteReviews\Addon\Forms\Commands\RegisterPostType;
use GeminiLabs\SiteReviews\Addon\Forms\Metaboxes\FieldsMetabox;
use GeminiLabs\SiteReviews\Addon\Forms\Metaboxes\HelpMetabox;
use GeminiLabs\SiteReviews\Addon\Forms\Metaboxes\TemplateMetabox;
use GeminiLabs\SiteReviews\Addon\Forms\Metaboxes\TemplateTagsMetabox;
use GeminiLabs\SiteReviews\Addon\Forms\Sanitizers\SanitizeFormId;
use GeminiLabs\SiteReviews\Addon\Forms\SearchForms;
use GeminiLabs\SiteReviews\Addon\Forms\Shortcodes\SiteReviewsFieldShortcode;
use GeminiLabs\SiteReviews\Addon\Forms\Tinymce\SiteReviewsFieldTinymce;
use GeminiLabs\SiteReviews\Addon\Forms\Widgets\SiteReviewsFieldWidget;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\MetaboxForm;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;
use GeminiLabs\SiteReviews\Role;

class Controller extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @action admin_enqueue_scripts
     */
    public function enqueueAdminAssets(): void
    {
        if ($this->isReviewAdminPage()) {
            $this->enqueueAsset('css', [
                'dependencies' => [
                    'wp-codemirror',
                    'wp-edit-post',
                ],
                'suffix' => 'admin',
            ]);
            $this->enqueueAsset('js', [
                'dependencies' => [glsr()->id.'/admin', 'backbone', 'wp-api-fetch'],
                'suffix' => 'admin',
            ]);
            $codemirror = wp_enqueue_code_editor([
                'codemirror' => ['indentWithTabs' => false, 'lineWrapping' => false],
                'htmlhint' => ['space-tab-mixed-disabled' => 'space'],
                'type' => 'text/html',
            ]);
            wp_localize_script('jquery', 'cm_settings', ['codeEditor' => $codemirror]);
        }
    }

    /**
     * @action enqueue_block_editor_assets
     */
    public function enqueueBlockAssets(): void
    {
        // The admin dependency loads this before the Site Reviews blocks script as block filters must be loaded first.
        $this->enqueueAsset('js', [
            'dependencies' => [glsr()->id.'/blocks'],
            'suffix' => 'blocks',
        ]);
    }

    /**
     * @action wp_enqueue_scripts
     */
    public function enqueuePublicAssets(): void
    {
        parent::enqueuePublicAssets();
        $library = glsr_get_option('addons.forms.dropdown_library');
        $loadLibraryFiles = glsr_get_option('addons.forms.dropdown_assets');
        if ('choices.js' === $library && 'yes' === $loadLibraryFiles) {
            $version = $this->app()->filterString('library/'.$library, '10.1.0');
            $cssUrl = sprintf('https://cdn.jsdelivr.net/npm/choices.js@%s/public/assets/styles/choices.min.css', $version);
            $jsUrl = sprintf('https://cdn.jsdelivr.net/npm/choices.js@%s/public/assets/scripts/choices.min.js', $version);
            $script = $this->app()->filterString('enqueue/'.$library, 'GLSR.Event.on("site-reviews/loaded",function(){"undefined"!==typeof Choices&&document.querySelectorAll(".glsr select:not(.browser-default)").forEach(function(a){GLSR.addons["site-reviews-forms"].Choices = new Choices(a,GLSR.addons["site-reviews-forms"].choicesjs)})})');
            wp_enqueue_script(glsr()->id.'/choices', $jsUrl, [glsr()->id], $version, true);
            wp_enqueue_style(glsr()->id.'/choices', $cssUrl, [glsr()->id], $version);
            wp_add_inline_script(glsr()->id.'/choices', $script);
        }
    }

    /**
     * @filter site-reviews/enqueue/admin/localize
     */
    public function filterAdminLocalizedVariables(array $variables): array
    {
        $variables = Arr::set($variables, 'hideoptions.site_reviews_field',
            glsr(SiteReviewsFieldShortcode::class)->getHideOptions()
        );
        return $variables;
    }

    /**
     * @filter site-reviews/block/form/attributes
     * @filter site-reviews/block/review/attributes
     * @filter site-reviews/block/reviews/attributes
     * @filter site-reviews-images/block/images/attributes
     */
    public function filterBlockAttributes(array $attributes): array
    {
        $attributes['form'] = [
            'default' => '',
            'type' => 'string',
        ];
        return $attributes;
    }

    /**
     * @param bool   $useBlockEditor
     * @param string $postType
     *
     * @filter use_block_editor_for_post_type:999
     */
    public function filterBlockEditor($useBlockEditor, $postType): bool
    {
        if (Application::POST_TYPE === $postType) {
            return false;
        }
        return Cast::toBool($useBlockEditor);
    }

    /**
     * @filter site-reviews/documentation/faq
     */
    public function filterDocumentationFaq(array $sections): array
    {
        $sections[] = glsr(Application::class)->file('faq/enable-optgroup');
        return $sections;
    }

    /**
     * @filter site-reviews/documentation/shortcodes
     */
    public function filterDocumentationShortcodes(array $sections): array
    {
        $sections['site_reviews_field'] = $this->app()->path('views/documentation/site_reviews_field.php');
        return $sections;
    }

    /**
     * @filter site-reviews/interpolate/form/field_assigned_posts
     * @filter site-reviews/interpolate/form/field_assigned_terms
     * @filter site-reviews/interpolate/form/field_assigned_users
     */
    public function filterFieldContext(array $context): array
    {
        $context['field_type'] = 'select';
        return $context;
    }

    /**
     * @filter site-reviews/build/template/form/field_assigned_posts
     * @filter site-reviews/build/template/form/field_assigned_terms
     * @filter site-reviews/build/template/form/field_assigned_users
     * @filter site-reviews/build/template/form/field_checkbox
     * @filter site-reviews/build/template/form/field_date
     * @filter site-reviews/build/template/form/field_dropzone
     * @filter site-reviews/build/template/form/field_email
     * @filter site-reviews/build/template/form/field_number
     * @filter site-reviews/build/template/form/field_radio
     * @filter site-reviews/build/template/form/field_range
     * @filter site-reviews/build/template/form/field_rating
     * @filter site-reviews/build/template/form/field_select
     * @filter site-reviews/build/template/form/field_tel
     * @filter site-reviews/build/template/form/field_text
     * @filter site-reviews/build/template/form/field_textarea
     * @filter site-reviews/build/template/form/field_toggle
     * @filter site-reviews/build/template/form/field_url
     */
    public function filterFieldTemplate(string $template): string
    {
        if (str_contains($template, '{{ description')) {
            return $template;
        }
        $position = $this->app()->option('field_description');
        if ('under_field' === $position) {
            return str_replace('{{ errors }}', '{{ description }} {{ errors }}', $template);
        }
        if ('under_label' === $position) {
            if (str_contains($template, '{{ label }}')) {
                return str_replace('{{ label }}', '{{ label }} {{ description }}', $template);
            }
            if (str_contains($template, '{{ field }}')) {
                return str_replace('{{ field }}', '{{ description }} {{ field }}', $template);
            }
        }
        return $template;
    }

    /**
     * @filter site-reviews/enqueue/public/inline-styles
     */
    public function filterInlinePublicStyles(string $styles): string
    {
        $colSpan = [
            25 => 3,
            33 => 4,
            50 => 6,
            66 => 8,
            75 => 9,
            100 => 12,
        ];
        $screens = [
            'sm' => '',
            'md' => $this->app()->option('responsive_width.md'),
            'lg' => $this->app()->option('responsive_width.lg'),
            'xl' => $this->app()->option('responsive_width.xl'),
        ];
        $styles .= '.glsr form.glsr-form-responsive{gap:var(--glsr-gap-md);grid-template-columns:repeat(12,1fr)}';
        $styles .= 'form.glsr-form-responsive>*{grid-column: span 12}';
        foreach ($screens as $screen => $width) {
            $screen = 'sm' === $screen ? '' : "{$screen}\:";
            $sizes = '';
            foreach ($colSpan as $col => $span) {
                $sizes .= sprintf('.glsr-form-responsive>.%sgl-col-%d{grid-column:span %d}', $screen, $col, $span);
            }
            if (!empty($width)) {
                $sizes = sprintf('@media (min-width:%spx){%s}', $width, $sizes);
            }
            $styles .= $sizes;
        }
        return $styles;
    }

    /**
     * @filter site-reviews/enqueue/public/localize
     */
    public function filterLocalizedPublicVariables(array $variables): array
    {
        $library = glsr_get_option('addons.forms.dropdown_library');
        $loadLibraryFiles = glsr_get_option('addons.forms.dropdown_assets');
        if ('choices.js' === $library && 'yes' === $loadLibraryFiles) {
            $variables['addons'][Application::ID] = [
                'choicesjs' => [
                    'itemSelectText' => '',
                    'position' => 'bottom',
                    'shouldSort' => true,
                ],
            ];
        }
        return $variables;
    }

    /**
     * @filter site-reviews/sanitizer/form-id
     */
    public function filterSanitizerFormId(): string
    {
        return SanitizeFormId::class;
    }

    /**
     * @filter site-reviews/defer-scripts
     */
    public function filterScriptsDefer(array $handles): array
    {
        $handles[] = glsr()->id.'/choices';
        return $handles;
    }

    /**
     * @filter site-reviews/defaults/site-review/defaults
     * @filter site-reviews/defaults/site-reviews/defaults
     * @filter site-reviews/defaults/site-reviews-form/defaults
     * @filter site-reviews-images/defaults/site-reviews-images/defaults
     */
    public function filterShortcodeDefaults(array $defaults): array
    {
        $defaults['form'] = '';
        return $defaults;
    }

    /**
     * @filter site-reviews/defaults/site-review/sanitize
     * @filter site-reviews/defaults/site-reviews/sanitize
     * @filter site-reviews/defaults/site-reviews-form/sanitize
     * @filter site-reviews-images/defaults/site-reviews-images/sanitize
     */
    public function filterShortcodeSanitize(array $sanitize): array
    {
        $sanitize['form'] = 'form-id';
        return $sanitize;
    }

    /**
     * @filter site-reviews/style/views
     */
    public function filterStyleViews(array $views): array
    {
        $field = Arr::get($views, 0, '');
        $basename = basename($field);
        if (!in_array($basename, ['field_assigned_posts', 'field_assigned_terms', 'field_assigned_users'])) {
            return $views;
        }
        $genericField = Arr::get($views, 1, '');
        $views = Arr::insertAfter(0, $views, [$genericField.'_select']);
        return $views;
    }

    /**
     * @action {$this->app()->id}/activate
     */
    public function install(): void
    {
        glsr(Role::class)->resetAll();
    }

    /**
     * Since this is an AJAX request, We need to set the global $post in order
     * for `get_the_ID` to work in the "site-reviews/metabox-form/fields" hook.
     *
     * @action site-reviews/route/ajax/metabox-details
     */
    public function metaboxDetailsAjax(Request $request): void
    {
        global $post;
        $formId = $request->cast('form_id', 'int');
        $postId = $request->cast('post_id', 'int');
        $post = get_post($postId);
        update_post_meta($postId, '_custom_form', $formId);
        $review = glsr_get_review($postId);
        $results = (new MetaboxForm($review))->build();
        wp_send_json_success([
            'items' => $results,
        ]);
    }

    /**
     * @action init
     */
    public function registerBlocks(): void
    {
        glsr(SiteReviewsFieldBlock::class)->register();
    }

    /**
     * @param \WP_Post $post
     *
     * @action add_meta_boxes_{Application::POST_TYPE}
     */
    public function registerMetaBoxes($post): void
    {
        glsr(HelpMetabox::class)->register($post);
        glsr(TemplateMetabox::class)->register($post);
        glsr(TemplateTagsMetabox::class)->register($post);
    }

    /**
     * @action init:8
     */
    public function registerPostType(): void
    {
        $this->execute(new RegisterPostType());
    }

    /**
     * @action init
     */
    public function registerShortcodes(): void
    {
        glsr(SiteReviewsFieldShortcode::class)->register();
    }

    /**
     * @action admin_init
     */
    public function registerTinymcePopups(): void
    {
        glsr(SiteReviewsFieldTinymce::class)->register();
    }

    /**
     * @action widgets_init
     */
    public function registerWidgets(): void
    {
        register_widget(SiteReviewsFieldWidget::class);
    }

    /**
     * @param \WP_Post $post
     *
     * @action edit_form_after_editor
     */
    public function renderFields($post): void
    {
        if (Application::POST_TYPE === get_post_type($post)) {
            glsr(FieldsMetabox::class)->render($post);
        }
    }

    /**
     * Manually change the position of the "All Forms" menu.
     *
     * @action admin_menu:11
     */
    public function reorderMenu(): void
    {
        global $submenu;
        $prefix = 'edit.php?post_type='.glsr()->post_type;
        $formPrefix = 'edit.php?post_type='.Application::POST_TYPE;
        if (empty($submenu[$prefix])) {
            return;
        }
        $menu = $submenu[$prefix];
        $orderedMenu = [];
        $search = array_search($formPrefix, wp_list_pluck($menu, 2));
        if (false === $search) {
            return;
        }
        foreach ($menu as $index => $page) {
            if ($formPrefix !== $page[2]) {
                $orderedMenu[$index] = $page;
            }
            if ($prefix === $page[2]) {
                $orderedMenu[$index + 1] = $menu[$search];
            }
        }
        $submenu[$prefix] = $orderedMenu;
    }

    /**
     * @param int $postId
     *
     * @action save_post_{Application::POST_TYPE}
     */
    public function saveMetaboxes($postId): void
    {
        $postId = Cast::toInt($postId);
        glsr(FieldsMetabox::class)->save($postId);
        glsr(TemplateMetabox::class)->save($postId);
    }

    /**
     * @action site-reviews/route/ajax/filter-form
     */
    public function searchFormsAjax(Request $request): void
    {
        $search = glsr(Sanitizer::class)->sanitizeText($request->search);
        $results = glsr(SearchForms::class)->search($search)->results();
        wp_send_json_success([
            'items' => $results,
        ]);
    }

    /**
     * @action site-reviews/review/request
     * @action site-reviews-authors/review/request
     */
    public function setOverallRating(Request $request): void
    {
        if (!empty($request->rating)) {
            return; // don't set the overall rating if it already has a value
        }
        $fields = (array) get_post_meta($request->cast('form', 'int'), '_fields', true);
        $ratings = [];
        foreach ($fields as $field) {
            if ('rating' === Arr::get($field, 'type')) {
                $fieldname = Arr::get($field, 'name');
                $ratings[] = $request->cast($fieldname, 'int');
            }
        }
        $ratings = array_filter($ratings); // remove empty custom rating values;
        if (empty($ratings)) {
            return; // don't set the overall rating if there are no custom rating fields
        }
        $ratingsCount = count($ratings);
        $ratingsSum = array_sum($ratings);
        $request->rating = round($ratingsSum / $ratingsCount);
    }

    protected function isReviewAdminPage(): bool
    {
        return glsr()->isAdmin()
            && (Application::POST_TYPE === get_post_type() || $this->isReviewEditor());
    }
}
