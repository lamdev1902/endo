<?php

namespace GeminiLabs\SiteReviews\Addon\Themes;

use GeminiLabs\SiteReviews\Addon\Themes\Defaults\TagDefaults;
use GeminiLabs\SiteReviews\Addon\Themes\Mock\MockTag;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;

class Theme
{
    public int $formId;
    public int $themeId;

    public function __construct(int $formId = 0, int $themeId = 0)
    {
        $this->themeId($themeId); // do this first!
        $this->formId($formId);
    }

    /**
     * Get the saved Theme Builder data.
     */
    public function builder(): array
    {
        return [
            'data' => [
                'fields' => glsr(ThemeBuilder::class, $this->params())->toArray(),
                'styles' => $this->presets(),
            ],
            'label' => _x('Builder', 'admin-text', 'site-reviews-themes'),
        ];
    }

    public function defaultTags(): array
    {
        $defaults = [];
        if (!glsr()->addon('site-reviews-forms') || empty($this->formId)) {
            $defaults = [
                'content' => 'review_content',
                'rating' => 'review_rating',
                'title' => 'review_title',
            ];
            if (glsr()->addon('site-reviews-images')) {
                $defaults['images'] = 'review_images';
            }
        }
        $tags = glsr(TagDefaults::class)->merge($defaults);
        array_walk($tags, function (&$type, $tag) {
            $type = compact('tag', 'type');
        });
        return array_values($tags);
    }

    public function formId(int $postId): self
    {
        if (empty(glsr()->addon('site-reviews-forms'))) {
            $postId = 0;
        } elseif (empty($this->themeId)) {
            // do nothing
        } elseif (glsr('site-reviews-forms')->post_type !== get_post_type($postId)) {
            $postId = Cast::toInt(get_post_meta($this->themeId, '_form', true));
        }
        $this->formId = $postId;
        return $this;
    }

    public function forms(): array
    {
        $forms = ['' => _x('Default Form', 'admin-text', 'site-reviews-themes')];
        if ($addon = glsr()->addon('site-reviews-forms')) {
            $forms += glsr($addon)->posts();
        }
        return $forms;
    }

    /**
     * Get the style presets.
     */
    public function presets(): array
    {
        $presets = [];
        $settings = Arr::unflatten(glsr(Application::class)->config('theme-settings'));
        $options = Arr::consolidate(Arr::get($settings, 'presentation.layout.appearance.options'));
        foreach ($options as $style => $label) {
            if ($config = glsr(Application::class)->config('presets/'.$style)) {
                $presets[$style] = Arr::unflatten($config);
            }
        }
        return $presets;
    }

    /**
     * Get the Theme Preview data.
     */
    public function preview(): array
    {
        return [
            'data' => [], // leave empty for now
            'label' => _x('Preview', 'admin-text', 'site-reviews-themes'),
        ];
    }

    /**
     * Get an array of reviews to use in the Theme Preview.
     */
    public function reviews(): array
    {
        $data = [];
        $reviews = glsr_get_reviews([
            'display' => 12,
            'form' => $this->formId,
            'raw' => true, // don't wrap fields and ignore hide options
            'theme' => $this->themeId,
        ]);
        $html = $reviews->build();
        $tags = array_fill_keys(wp_list_pluck($this->tags(), 'tag'), '');
        foreach ($html->rendered as $review) {
            $context = array_intersect_key($review->context, $tags);
            $data[] = $context;
        }
        return $data;
    }

    /**
     * Get the saved Theme Settings.
     */
    public function settings(): array
    {
        $settings = glsr(ThemeSettings::class, $this->params())->settings();
        return [
            'presentation' => [
                'data' => Arr::get($settings, 'presentation', []),
                'label' => _x('Presentation', 'admin-text', 'site-reviews-themes'),
            ],
            'design' => [
                'data' => Arr::get($settings, 'design', []),
                'label' => _x('Design', 'admin-text', 'site-reviews-themes'),
            ],
        ];
    }

    /**
     * Get all of the available SVG star images.
     */
    public function stars(): array
    {
        $images = [];
        $dir = glsr(Application::class)->path('assets/images/rating');
        if (is_dir($dir)) {
            $iterator = new \DirectoryIterator($dir);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isFile() && 'svg' === $fileinfo->getExtension()) {
                    $slug = $fileinfo->getBasename('.svg');
                    ob_start();
                    include $fileinfo->getPathname();
                    $image = ob_get_clean();
                    $images[$slug] = $image;
                    if (str_ends_with($slug, '-5')) {
                        $altSlug = substr($slug, 0, -2);
                        $images[$altSlug] = $image;
                    }
                }
            }
        }
        ksort($images, SORT_NATURAL);
        return $images;
    }

    public function swiperParameters(): array
    {
        $layout = glsr(ThemeSettings::class, $this->params())->get('presentation.layout');
        $library = glsr(Application::class)->option('swiper_library', 'swiper');
        $options = Arr::getAs('array', $layout, 'swiper_options');
        $spacing = Arr::getAs('int', $layout, 'spacing');
        $maxSlides = Arr::getAs('int', $layout, 'max_slides');
        $maxSlides = Helper::ifEmpty($maxSlides, 6, true);
        if ('swiper' === $library) {
            $breakpoints = [];
            $minWidth = 320;
            for ($i = 1; $i <= $maxSlides; ++$i) {
                $breakpoints[$minWidth * $i] = ['slidesPerView' => $i];
            }
            return [
                'autoplay' => in_array('autoplay', $options),
                'breakpoints' => $breakpoints,
                'navigation' => in_array('navigation', $options),
                'pagination' => in_array('pagination', $options),
                'spaceBetween' => $spacing,
            ];
        }
        if ('splide' === $library) {
            return [
                'autoplay' => in_array('autoplay', $options),
                'gap' => $spacing,
                'maxslides' => $maxSlides,
                'navigation' => in_array('navigation', $options),
                'pagination' => in_array('pagination', $options),
            ];
        }
        return [];
    }

    public function tags(): array
    {
        $fields = $this->defaultTags();
        if (glsr()->addon('site-reviews-forms') && !empty($this->formId)) {
            $formFields = glsr('Addon\Forms\FormFields')->indexedFields($this->formId);
            $formFields = array_filter($formFields, function ($field) {
                return !empty($field['tag']);
            });
            foreach ($fields as $field) {
                if (false === array_search($field['tag'], array_column($formFields, 'tag'))) {
                    $formFields[] = $field;
                }
            }
            $fields = $formFields;
        }
        array_walk($fields, function (&$field) {
            $field = (new MockTag($field))->toArray();
        });
        $fields = array_values(array_unique($fields, SORT_REGULAR));
        $tags = wp_list_pluck($fields, 'tag');
        array_multisort($tags, SORT_ASC, $fields);
        return $fields;
    }

    public function themeId(int $postId): self
    {
        if (Application::POST_TYPE !== get_post_type($postId)) {
            $postId = 0;
        }
        $this->themeId = $postId;
        return $this;
    }

    protected function params(): array
    {
        return [
            'formId' => $this->formId,
            'themeId' => $this->themeId,
        ];
    }
}
