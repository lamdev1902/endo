<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Controllers;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Addon\Images\Database\Query;
use GeminiLabs\SiteReviews\Addon\Images\Defaults\SiteReviewsImagesDefaults;
use GeminiLabs\SiteReviews\Addon\Images\Shortcodes\SiteReviewsImagesShortcode;
use GeminiLabs\SiteReviews\Addon\Images\Template;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\HookProxy;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Style;
use GeminiLabs\SiteReviews\Request;

class GridController
{
    use HookProxy;

    /**
     * @action site-reviews/route/ajax/fetch-image-gallery
     */
    public function fetchImageGalleryAjax(Request $request): void
    {
        $atts = glsr(SiteReviewsImagesDefaults::class)->restrict($request->cast('atts', 'array'));
        unset($atts['display']); // this is only used for the number of thumbnails displayed with the shortcode
        $results = glsr(Query::class)->reviewImages(wp_parse_args($request->toArray(), $atts));
        $images = $results['results'];
        unset($results['results']);
        $html = glsr(Application::class)->build('views/grid/images', compact('images', 'results'));
        wp_send_json_success([
            'data' => $results,
            'html' => $html,
            'pagination' => glsr(Application::class)->build('views/grid/pagination', $results),
        ]);
    }

    /**
     * @action site-reviews/route/ajax/fetch-image-review
     */
    public function fetchImageReviewAjax(Request $request): void
    {
        $atts = $request->cast('atts', 'array');
        $image = glsr(Query::class)->reviewImage($request->index, $atts);
        if (empty($image)) {
            wp_send_json_error([
                'code' => 404,
                'message' => __('The image was not found, please try refreshing the page.', 'site-reviews-images'),
            ]);
        }
        $results = glsr(Query::class)->reviewImages([
            'per_page' => 100, // get all images from the review
            'post__in' => $image->review_id,
        ]);
        $images = $results['results'];
        $firstIndex = $image->index - (int) array_search($image->ID, wp_list_pluck($images, 'ID'));
        $index = $firstIndex;
        foreach ($images as &$img) {
            $img->index = $index++; // fix the indexes
            $img = $img->toArray(['large']);
        }
        $total = $request->get('total', function () use ($atts) {
            return glsr(Query::class)->totalReviewImages($atts);
        });
        wp_send_json_success([
            'has_next' => $index < $total,
            'has_prev' => $firstIndex > 0,
            'images' => $images,
            'review' => $this->buildReview($image->review_id, $request),
            'review_id' => $image->review_id,
            'slider' => $this->buildSwiper($total),
            'slides' => $this->buildSwiperSlides($images, $atts),
            'total' => $total,
        ]);
    }

    /**
     * @filter site-reviews/enqueue/admin/localize
     */
    public function filterLocalizedAdminVariables(array $variables): array
    {
        $variables = Arr::set($variables, 'hideoptions.site_reviews_images',
            glsr(SiteReviewsImagesShortcode::class)->getHideOptions()
        );
        return $variables;
    }

    /**
     * @filter site-reviews/router/public/unguarded-actions
     */
    public function filterUnguardedActions(array $actions): array
    {
        $actions[] = 'fetch-image-gallery';
        $actions[] = 'fetch-image-review';
        return $actions;
    }

    protected function buildReview(int $reviewId, Request $request): string
    {
        $atts = $request->cast('atts', 'array');
        $hide = Arr::consolidate(json_decode(Arr::get($atts, 'hide')));
        if (in_array('review', $hide)) {
            return '';
        }
        // set the form in case the theme is using one
        // @todo: The Review Themes add-on should set the form if it uses one
        if (!empty($atts['theme']) && empty($atts['form'])) {
            $atts['form'] = glsr(Database::class)->meta($atts['theme'], 'form');
        }
        $data = glsr(SiteReviewsImagesDefaults::class)->dataAttributes($atts);
        // filter the content tag and remove the {{ images }} tag from the review
        glsr()->store('image_review', true);
        $review = glsr_get_review($reviewId)->build($atts);
        $html = glsr(Builder::class)->div(wp_parse_args($data, [
            'class' => glsr(Style::class)->styleClasses(),
            'data-review_id' => $reviewId,
            'text' => (string) $review,
        ]));
        glsr()->discard('image_review');
        return glsr(Template::class)->minify($html);
    }

    protected function buildSwiper(int $total): string
    {
        $html = glsr(Template::class)->build('views/grid/splide/swiper', [
            'context' => [
                'label' => '',
                'total' => $total,
            ],
        ]);
        return glsr(Template::class)->minify($html);
    }

    protected function buildSwiperSlides(array $images, array $atts): array
    {
        $hide = Arr::consolidate(json_decode(Arr::get($atts, 'hide')));
        $slides = [];
        foreach ($images as $context) {
            $html = glsr(Template::class)->build('views/grid/splide/slide', compact('context', 'hide'));
            $slides[] = glsr(Template::class)->minify($html);
        }
        return $slides;
    }
}
