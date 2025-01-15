<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Controllers;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\FieldElements\ThemedRating;
use GeminiLabs\SiteReviews\Addon\Themes\Html;
use GeminiLabs\SiteReviews\Addon\Themes\Partials\StarRating;
use GeminiLabs\SiteReviews\Addon\Themes\Style;
use GeminiLabs\SiteReviews\Addon\Themes\Tags\AvatarTag;
use GeminiLabs\SiteReviews\Addon\Themes\Tags\ContentTag;
use GeminiLabs\SiteReviews\Addon\Themes\Tags\TextareaTag;
use GeminiLabs\SiteReviews\Addon\Themes\Theme;
use GeminiLabs\SiteReviews\Addon\Themes\ThemeBuilder;
use GeminiLabs\SiteReviews\Addon\Themes\ThemeSettings;
use GeminiLabs\SiteReviews\Contracts\FieldContract;
use GeminiLabs\SiteReviews\Contracts\FormContract;
use GeminiLabs\SiteReviews\Contracts\ShortcodeContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\HookProxy;
use GeminiLabs\SiteReviews\Modules\Html\Partials\StarRating as StarRatingPartial;
use GeminiLabs\SiteReviews\Modules\Html\ReviewHtml;
use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewTag;

class ThemeController
{
    use HookProxy;

    /**
     * @filter site-reviews-forms/custom/tag/textarea
     */
    public function filterCustomTagTextarea(string $className, ReviewHtml $reviewHtml): string
    {
        if (empty(glsr()->addon('site-reviews-forms'))) {
            return $className;
        }
        if (!$this->isTheme(glsr()->args($reviewHtml->args)->theme)) {
            return $className;
        }
        return TextareaTag::class;
    }

    /**
     * @filter site-reviews/field/element/rating
     */
    public function filterFieldElementRating(string $className, FieldContract $field): string
    {
        if (!$this->isTheme($field->theme)) {
            return $className;
        }
        return ThemedRating::class;
    }

    /**
     * @filter site-reviews/review-form/fields
     */
    public function filterReviewFormFields(array $fields, FormContract $form): array
    {
        if ($this->isTheme($form->args()->theme)) {
            $ratingImage = glsr(ThemeSettings::class)
                ->themeId($form->args()->cast('theme', 'int'))
                ->get('design.rating.rating_image');
            if ('default' === $ratingImage) {
                return $fields;
            }
            foreach ($fields as &$field) {
                if ('rating' === $field['type']) {
                    $field['theme'] = $form->args()->theme;
                    // $field['type'] = 'themed-rating';
                }
            }
        }
        return $fields;
    }

    /**
     * @filter site-reviews/interpolate/reviews
     */
    public function filterReviewsContext(array $context, string $template, array $data): array
    {
        $themeId = Arr::getAs('int', $data, 'args.theme');
        $layout = glsr(ThemeSettings::class)->themeId($themeId)->get('presentation.layout');
        if (!empty($layout)) {
            $displayAs = Arr::get($layout, 'display_as');
            if ('carousel' === $displayAs) {
                $options = glsr(Theme::class)->themeId($themeId)->swiperParameters();
                $context['class'] = '';
                $context['options'] = json_encode($options, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_NUMERIC_CHECK);
            } elseif ('grid' === $displayAs) {
                $context['class'] .= sprintf(' gl-grid-%s', Arr::get($layout, 'max_columns'));
            }
        }
        return $context;
    }

    /**
     * @filter site-reviews/reviews/html/theme
     */
    public function filterReviewsHtmlTheme(string $value, ReviewHtml $reviewHtml): string
    {
        if ($themeId = Arr::getAs('int', $reviewHtml, 'args.theme')) {
            return glsr(Style::class)->themeId($themeId)->get()->toString();
        }
        return $value;
    }

    /**
     * @filter site-reviews/build/template/reviews
     */
    public function filterReviewsTemplate(string $template, array $data): string
    {
        $themeId = Arr::getAs('int', $data, 'args.theme');
        $settings = glsr(ThemeSettings::class)->themeId($themeId);
        $displayAs = $settings->get('presentation.layout.display_as');
        if (empty($displayAs)) {
            return $template;
        }
        if ('modal' === $settings->get('presentation.excerpt.excerpt_action')) {
            glsr()->store('use_modal', true);
        }
        if ('carousel' === $displayAs) {
            $swiper = glsr(Application::class)->option('swiper_library', 'swiper');
            return glsr(Application::class)->build('templates/reviews-'.$swiper);
        }
        return glsr(Application::class)->build('templates/reviews-'.$displayAs);
    }

    /**
     * @filter site-reviews/build/template/review
     */
    public function filterReviewTemplate(string $template, array $data): string
    {
        $themeId = Arr::getAs('int', $data, 'args.theme');
        if ($themeTemplate = glsr(Html::class)->build($themeId)) {
            return $themeTemplate;
        }
        return $template;
    }

    /**
     * @filter site-reviews/shortcode/site_review/attributes
     * @filter site-reviews/shortcode/site_reviews/attributes
     * @filter site-reviews/shortcode/site_reviews_images/attributes
     * @filter site-reviews/shortcode/site_reviews_form/attributes
     * @filter site-reviews/shortcode/site_reviews_summary/attributes
     */
    public function filterShortcodeAttributes(array $attributes, ShortcodeContract $shortcode): array
    {
        return $this->modifyShortcodeAttributes($attributes, $shortcode);
    }

    /**
     * @filter site-reviews/partial/classname
     */
    public function filterStarRatingPartial(string $className, string $path, array $data): string
    {
        if (StarRatingPartial::class !== $className) {
            return $className;
        }
        $themeId = Arr::getAs('int', $data, 'args.theme');
        if (!$this->isTheme($themeId)) {
            return $className;
        }
        // $theme = glsr(ThemeSettings::class)->themeId($themeId);
        // if ('default' === $theme->get('design.rating.rating_image')) {
        //     return $className;
        // }
        return StarRating::class;
    }

    /**
     * @filter site-reviews/review/tag/avatar
     */
    public function filterTagAvatar(string $className, ReviewHtml $reviewHtml): string
    {
        $themeId = Arr::getAs('int', $reviewHtml->args, 'theme');
        if ($this->isTheme($themeId)) {
            return AvatarTag::class;
        }
        return $className;
    }

    /**
     * @filter site-reviews/review/tag/content
     */
    public function filterTagContent(string $className, ReviewHtml $reviewHtml): string
    {
        $themeId = Arr::getAs('int', $reviewHtml->args, 'theme');
        if ($this->isTheme($themeId)) {
            return ContentTag::class;
        }
        return $className;
    }

    /**
     * @param int $postId
     *
     * @action save_post_{Application::POST_TYPE}
     */
    public function saveTheme($postId): void
    {
        $params = ['themeId' => $postId];
        update_post_meta($postId, '_form', filter_input(INPUT_POST, 'form'));
        if ($settings = filter_input(INPUT_POST, 'theme_settings')) {
            $settings = Cast::toArray(json_decode($settings, true));
            glsr(ThemeSettings::class, $params)->save($settings);
        }
        if ($builder = filter_input(INPUT_POST, 'theme_builder')) {
            $builder = Cast::toArray(json_decode($builder, true));
            glsr(ThemeBuilder::class, $params)->save($builder);
        }
    }

    protected function isTheme($postId): bool
    {
        return Application::POST_TYPE === get_post_type(Cast::toInt($postId));
    }

    protected function modifyShortcodeAttributes(array $attributes, ShortcodeContract $shortcode): array
    {
        if (!$themeId = Arr::getAs('int', $attributes, 'data-theme')) {
            return $attributes;
        }
        $style = glsr(Style::class)->themeId($themeId);
        if (in_array($shortcode->shortcode, ['site_review', 'site_reviews'])) {
            $attributes['style'] = $style->toString();
        }
        $ratingImage = glsr(ThemeSettings::class)->themeId($themeId)->get('design.rating.rating_image');
        if ('default' === $ratingImage) {
            return $attributes;
        }
        if ('site_reviews_form' === $shortcode->shortcode) {
            $style->only([
                '--gl-rating-color-0', '--gl-rating-color-1', '--gl-rating-color-2',
                '--gl-rating-color-3', '--gl-rating-color-4', '--gl-rating-color-5',
            ]);
            $attributes['style'] = $style->toString();
        }
        if ('site_reviews_summary' === $shortcode->shortcode) {
            $style->only([
                '--gl-rating-color-0', '--gl-rating-color-1', '--gl-rating-color-2',
                '--gl-rating-color-3', '--gl-rating-color-4', '--gl-rating-color-5',
                '--gl-rating-size',
            ]);
            $attributes['style'] = $style->toString();
        }
        return $attributes;
    }
}
