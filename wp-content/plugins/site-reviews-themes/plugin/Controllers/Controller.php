<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Controllers;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Commands\RegisterPostType;
use GeminiLabs\SiteReviews\Addon\Themes\Metaboxes\FormTagsMetabox;
use GeminiLabs\SiteReviews\Addon\Themes\Metaboxes\HelpMetabox;
use GeminiLabs\SiteReviews\Addon\Themes\Metaboxes\SubmitMetabox;
use GeminiLabs\SiteReviews\Addon\Themes\Sanitizers\SanitizeThemeId;
use GeminiLabs\SiteReviews\Addon\Themes\Template;
use GeminiLabs\SiteReviews\Addon\Themes\Theme;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Commands\EnqueuePublicAssets;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Assets\AssetCss;
use GeminiLabs\SiteReviews\Modules\Notice;
use GeminiLabs\SiteReviews\Request;
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
                    'wp-color-picker',
                    'wp-components',
                    'wp-edit-post',
                ],
                'suffix' => 'admin',
            ]);
            (new EnqueuePublicAssets())->enqueueStyles();
            wp_register_script('glsr_wp-color-picker-alpha', glsr(Application::class)->url('assets/wp-color-picker-alpha.min.js'), ['wp-color-picker'], '3.0.0');
            $this->enqueueAsset('js', [
                'dependencies' => [glsr()->id.'/admin', 'backbone', 'glsr_wp-color-picker-alpha', 'wp-api-fetch', 'wp-components'],
                'suffix' => 'admin',
            ]);
            $codemirror = wp_enqueue_code_editor([
                'codemirror' => ['indentWithTabs' => false, 'lineWrapping' => false],
                'htmlhint' => ['space-tab-mixed-disabled' => 'space'],
                'type' => 'text/html',
            ]);
            wp_localize_script('jquery', 'cm_settings', ['codeEditor' => $codemirror]);
            $this->enqueueSwiperAssets([Application::ID.'/admin']);
        }
    }

    /**
     * @action enqueue_block_editor_assets
     */
    public function enqueueBlockAssets(): void
    {
        $this->enqueueAsset('css', ['suffix' => 'blocks']);
        // The admin dependency loads this before the Site Reviews blocks script as block filters must be loaded first.
        $this->enqueueAsset('js', [
            'dependencies' => [glsr()->id.'/blocks'],
            'suffix' => 'blocks',
        ]);
        $this->enqueueSwiperAssets([glsr()->id.'/blocks']);
    }

    /**
     * @action wp_enqueue_scripts
     */
    public function enqueuePublicAssets(): void
    {
        parent::enqueuePublicAssets();
        if ('yes' === $this->app()->option('swiper_assets', 'yes')) {
            $this->enqueueSwiperAssets([glsr()->id]);
        }
    }

    /**
     * @filter site-reviews/block/form/attributes
     * @filter site-reviews/block/review/attributes
     * @filter site-reviews/block/reviews/attributes
     * @filter site-reviews/block/summary/attributes
     * @filter site-reviews-images/block/images/attributes
     */
    public function filterBlockAttributes(array $attributes): array
    {
        $attributes['theme'] = [
            'default' => '',
            'type' => 'string',
        ];
        return $attributes;
    }

    /**
     * @param bool $useBlockEditor
     * @param string $postType
     * @filter use_block_editor_for_post_type
     */
    public function filterBlockEditor($useBlockEditor, $postType): bool
    {
        if (Application::POST_TYPE === $postType) {
            return false;
        }
        return Cast::toBool($useBlockEditor);
    }

    /**
     * @filter site-reviews/defaults/custom-fields/guarded
     */
    public function filterGuardedCustomFields(array $guarded): array
    {
        $guarded[] = 'theme';
        return Arr::unique($guarded);
    }

    /**
     * @filter site-reviews/enqueue/public/inline-styles
     */
    public function filterInlineStyles(string $css): string
    {
        $inline = [
            '[data-stars=default] .glsr-rating-empty{background-image:var(--glsr-star-empty)}',
            '[data-stars=default] .glsr-rating-half{background-image:var(--glsr-star-half)}',
            '[data-stars=default] .glsr-rating-full{background-image:var(--glsr-star-full)}',
        ];
        return $css.implode('', $inline);
    }

    /**
     * @filter site-reviews/enqueue/admin/localize
     */
    public function filterLocalizedAdminVariables(array $variables): array
    {
        $variables['addons'][Application::ID] = [
            'error' => [
                'basic' => _x('Unable to load.', 'admin-text', 'site-reviews-themes'),
                'detailed' => _x('Unable to load the Theme Builder. Please read the Basic Troubleshooting steps on the Help page.', 'admin-text', 'site-reviews-themes'),
            ],
            // 'palettes' => ['#cccccc', '#ff6f31', '#ff9f02', '#ffcf02', '#9ace6a', '#57bb8a'],
            'palettes' => ['#dcdce6', '#ff3722', '#ff8622', '#ffce00', '#73cf11', '#00b67a'],
            'swiper' => $this->app()->option('swiper_library', 'swiper'),
            'swipers' => [],
        ];
        $variables['nonce']['theme'] = wp_create_nonce('theme');
        $variables['nonce']['theme-tags'] = wp_create_nonce('theme-tags');
        return $variables;
    }

    /**
     * @filter site-reviews/enqueue/public/localize
     */
    public function filterLocalizedPublicVariables(array $variables): array
    {
        $variables['addons'][Application::ID] = [
            'swiper' => $this->app()->option('swiper_library', 'swiper'),
            'swipers' => [],
        ];
        return $variables;
    }

    /**
     * @filter site-reviews/sanitizer/theme-id
     */
    public function filterSanitizerThemeId(): string
    {
        return SanitizeThemeId::class;
    }

    /**
     * @filter site-reviews/defaults/site-review
     * @filter site-reviews/defaults/site-reviews
     * @filter site-reviews/defaults/site-reviews-form
     * @filter site-reviews/defaults/site-reviews-images
     * @filter site-reviews/defaults/site-reviews-summary
     */
    public function filterShortcodeArgs(array $values, string $method, array $args): array
    {
        if (empty($args['theme'])) {
            return $values;
        }
        $theme = glsr(Theme::class, [
            'formId' => Arr::getAs('int', $args, 'form'),
            'themeId' => Arr::getAs('int', $args, 'theme'),
        ]);
        if ('dataAttributes' === $method) {
            $values['data-form'] = $theme->formId;
            $values['data-theme'] = $theme->themeId;
        } else {
            $values['form'] = $theme->formId;
            $values['theme'] = $theme->themeId;
        }
        return $values;
    }

    /**
     * @filter site-reviews/defaults/site-review/defaults
     * @filter site-reviews/defaults/site-reviews/defaults
     * @filter site-reviews/defaults/site-reviews-form/defaults
     * @filter site-reviews/defaults/site-reviews-images/defaults
     * @filter site-reviews/defaults/site-reviews-summary/defaults
     */
    public function filterShortcodeDefaults(array $defaults): array
    {
        $defaults['theme'] = '';
        return $defaults;
    }

    /**
     * @filter site-reviews/defaults/site-review/sanitize
     * @filter site-reviews/defaults/site-reviews/sanitize
     * @filter site-reviews/defaults/site-reviews-form/sanitize
     * @filter site-reviews/defaults/site-reviews-images/sanitize
     * @filter site-reviews/defaults/site-reviews-summary/sanitize
     */
    public function filterShortcodeSanitize(array $sanitize): array
    {
        $sanitize['theme'] = 'theme-id';
        return $sanitize;
    }

    /**
     * @action {addon_id}/activate
     */
    public function install(): void
    {
        glsr(Role::class)->resetAll();
    }

    /**
     * @param \WP_Post $post
     * @action add_meta_boxes_{Application::POST_TYPE}
     */
    public function registerMetaBoxes($post): void
    {
        glsr(FormTagsMetabox::class)->register($post);
        glsr(HelpMetabox::class)->register($post);
        glsr(SubmitMetabox::class)->register($post);
    }

    /**
     * @action init
     */
    public function registerPostType(): void
    {
        $this->execute(new RegisterPostType());
    }

    /**
     * @filter admin_notices
     */
    public function renderBetaNotice(): void
    {
        $screen = glsr_current_screen();
        $isCurrentScreen = str_starts_with(glsr_current_screen()->post_type, glsr(Application::class)->post_type);
        if ($isCurrentScreen) {
            glsr(Application::class)->render('beta-notice');
        }
    }

    /**
     * @param \WP_Post $post
     * @action edit_form_top
     */
    public function renderNotice($post): void
    {
        if (Application::POST_TYPE !== $post->post_type) {
            return;
        }
        $formId = get_post_meta($post->ID, '_form', true);
        if (empty($formId)) {
            return;
        }
        if (empty(glsr()->addon('site-reviews-forms'))) {
            echo glsr(Notice::class)
                ->addError(_x('This theme uses a custom form, but the Review Forms add-on is not activated.', 'admin-text', 'site-reviews-themes'))
                ->get();
            return;
        }
        $form = get_post($formId);
        if (empty($form) || $form->post_type !== glsr('site-reviews-forms')->post_type) {
            echo glsr(Notice::class)
                ->addError(_x('This theme uses a custom form, but the selected form no longer exists.', 'admin-text', 'site-reviews-themes'))
                ->get();
            return;
        }
    }

    /**
     * @action admin_footer
     */
    public function renderTemplates(): void
    {
        $screen = glsr_current_screen();
        if (Application::POST_TYPE === $screen->id && Application::POST_TYPE === $screen->post_type) {
            glsr(Template::class)->render('views/templates', [
                'context' => [ // $this->mockReviewFields()
                    'assigned_links' => '|assigned_links|',
                    'author' => '|author|',
                    'avatar' => '|avatar|',
                    'content' => '|content|',
                    'date' => '|date|',
                    'rating' => '|rating|',
                    'response' => '|response|',
                    'title' => '|title|',
                ],
            ]);
            glsr(Application::class)->action('templates'); // allows addons to add custom field templates
        }
    }

    /**
     * @param \WP_Post $post
     * @action edit_form_after_editor
     */
    public function renderTheme($post): void
    {
        if (Application::POST_TYPE === get_post_type($post)) {
            $formId = get_post_meta($post->ID, '_form', true);
            glsr()->render(Application::ID.'/views/metabox-theme');
        }
    }

    /**
     * Manually change the position of the "All Themes" menu.
     * @action admin_menu
     */
    public function reorderMenu(): void
    {
        global $submenu;
        $prefix = 'edit.php?post_type='.glsr()->post_type;
        $themePrefix = 'edit.php?post_type='.Application::POST_TYPE;
        if (empty($submenu[$prefix])) {
            return;
        }
        $menu = $submenu[$prefix];
        $orderedMenu = [];
        $search = array_search($themePrefix, wp_list_pluck($menu, 2));
        if (false === $search) {
            return;
        }
        foreach ($menu as $index => $page) {
            if ($themePrefix !== $page[2]) {
                $orderedMenu[$index] = $page;
            }
            if ($prefix === $page[2]) {
                $orderedMenu[$index + 2] = $menu[$search];
            }
        }
        $submenu[$prefix] = $orderedMenu;
    }

    /**
     * @action site-reviews/route/ajax/theme
     */
    public function themeAjax(Request $request): void
    {
        $theme = glsr(Theme::class, [
            'formId' => $request->cast('formid', 'int'),
            'themeId' => $request->cast('postid', 'int'),
        ]);
        wp_send_json_success([
            'reviews' => $theme->reviews(),
            'settings' => $theme->settings(),
            'stars' => $theme->stars(),
            'tags' => $theme->tags(),
            'theme' => [ // order is intentional
                'preview' => $theme->preview(),
                'builder' => $theme->builder(),
            ],
        ]);
    }

    /**
     * @action site-reviews/route/ajax/theme-tags
     */
    public function themeTagsAjax(Request $request): void
    {
        $theme = glsr(Theme::class, [
            'formId' => $request->cast('formid', 'int'),
            'themeId' => $request->cast('postid', 'int'),
        ]);
        wp_send_json_success([
            'reviews' => $theme->reviews(),
            'tags' => $theme->tags(),
        ]);
    }

    protected function enqueueSwiperAssets(array $dependencies): void
    {
        $libraries = $this->app()->filterArray('swiper/libraries', [
            'splide' => [
                'script' => 'https://cdn.jsdelivr.net/npm/@splidejs/splide@%s/dist/js/splide.min.js',
                'style' => 'https://cdn.jsdelivr.net/npm/@splidejs/splide@%s/dist/css/splide-core.min.css',
                'version' => '4.1',
            ],
            'swiper' => [
                'script' => 'https://cdn.jsdelivr.net/npm/swiper@%s/swiper-bundle.min.js',
                'style' => '',
                'version' => $this->app()->option('swiper_version', '8'),
            ],
        ]);
        $library = $this->app()->option('swiper_library', 'swiper');
        if (!array_key_exists($library, $libraries)) {
            return;
        }
        extract($libraries[$library]);
        if (glsr()->filterBool('assets/use-local', false)) {
            if ('splide' === $library) {
                $script = $this->app()->url(sprintf('assets/npm/splide-%s/splide.min.js', $version));
                $style = $this->app()->url(sprintf('assets/npm/splide-%s/splide-core.min.css', $version));
            }
            if ('swiper' === $library) {
                $script = $this->app()->url(sprintf('assets/npm/swiper-%s/swiper-bundle.min.js', $version));
            }
        }
        $handle = glsr()->id.'/'.$library;
        if (!empty($script)) {
            wp_enqueue_script($handle, sprintf($script, $version), $dependencies, $version, true);
        }
        if (!empty($style)) {
            wp_enqueue_style($handle, sprintf($style, $version), $dependencies, $version);
        }
    }

    protected function isReviewAdminPage(): bool
    {
        return glsr()->isAdmin() && Application::POST_TYPE === get_post_type();
    }
}
