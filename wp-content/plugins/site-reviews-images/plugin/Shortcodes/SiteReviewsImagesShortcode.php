<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Shortcodes;

use GeminiLabs\SiteReviews\Addon\Images\Database\Query;
use GeminiLabs\SiteReviews\Addon\Images\Template;
use GeminiLabs\SiteReviews\Shortcodes\Shortcode;

class SiteReviewsImagesShortcode extends Shortcode
{
    public array $args = [];

    public function buildTemplate(): string
    {
        glsr()->store('use_swiper', true); // this loads the splidejs script
        $results = glsr(Query::class)->reviewImages($this->args);
        $this->debug(compact('results'));
        $images = $results['results'];
        unset($results['results']);
        return glsr(Template::class)->build('templates/review-images', [
            'args' => $this->args,
            'images' => $images,
            'context' => [
                'class' => $this->args['class'],
                'images' => $this->buildTemplateTagImages($images),
                'link' => $this->buildTemplateTagLink($results),
            ],
            'results' => $results,
        ]);
    }

    protected function buildTemplateTagImages(array $images): string
    {
        if (empty($images)) {
            return '';
        }
        $renderedImages = array_reduce($images, function ($carry, $image) {
            $context = $image->toArray(['large', 'medium']);
            $rendered = glsr(Template::class)->build('templates/gallery/image', compact('context'));
            return $carry.$rendered;
        }, '');
        return glsr(Template::class)->build('templates/gallery/images', [
            'context' => [
                'images' => $renderedImages,
            ],
        ]);
    }

    protected function buildTemplateTagLink(array $results): string
    {
        if (empty($results['total'])) {
            return '';
        }
        return glsr(Template::class)->build('templates/gallery/link', [
            'context' => [
                'text' => __('See all images ', 'site-reviews-images'),
            ],
        ]);
    }

    protected function hideOptions(): array
    {
        return [
            'caption' => _x('Hide image captions', 'admin-text', 'site-reviews-images'),
            'review' => _x('Hide image reviews', 'admin-text', 'site-reviews-images'),
        ];
    }
}
