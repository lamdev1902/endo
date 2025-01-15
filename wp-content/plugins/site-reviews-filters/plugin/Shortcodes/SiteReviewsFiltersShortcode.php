<?php

namespace GeminiLabs\SiteReviews\Addon\Filters\Shortcodes;

use GeminiLabs\SiteReviews\Addon\Filters\Application;
use GeminiLabs\SiteReviews\Addon\Filters\Defaults\FilteredDefaults;
use GeminiLabs\SiteReviews\Addon\Filters\FilterForm;
use GeminiLabs\SiteReviews\Addon\Filters\Template;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Shortcodes\Shortcode;

class SiteReviewsFiltersShortcode extends Shortcode
{
    public array $args = [];

    public function buildTemplate(): string
    {
        $form = new FilterForm($this->args);
        // $formHtml = $this->isHidden() ? $form->build() : '';
        $this->debug(compact('form'));
        return glsr(Template::class)->build('templates/reviews-filter', [
            'args' => $this->args,
            'context' => [
                'class' => 'glsr-filters',
                'form' => $form->build(),
                'status' => $this->buildTemplateTag('status'),
            ],
        ]);
    }

    public function buildTemplateTag(string $tag): string
    {
        $args = $this->args;
        $className = Helper::buildClassName("$tag-tag", 'Addon\Filters\Tags');
        $field = class_exists($className)
            ? glsr($className, compact('tag', 'args'))->handleFor('filters')
            : '';
        return glsr(Application::class)->filterString("filters/build/{$tag}", $field, $this);
    }

    public function register(): void
    {
        if (!function_exists('add_shortcode')) {
            return;
        }
        parent::register();
        add_shortcode('site_reviews_filter', [$this, 'buildShortcode']); // @compat
    }

    protected function displayOptions(): array
    {
        $terms = glsr(Application::class)->categories();
        $options = [
            'filter_by_rating' => _x('Display the rating filter', 'admin-text', 'site-reviews-filters'),
            'search_for' => _x('Display the search bar', 'admin-text', 'site-reviews-filters'),
            'sort_by' => _x('Display the sort by', 'admin-text', 'site-reviews-filters'),
        ];
        if (!empty($terms)) {
            $options['filter_by_term'] = _x('Display the category filter', 'admin-text', 'site-reviews-filters');
        }
        natsort($options);
        return $options;
    }

    protected function hideOptions(): array
    {
        return [
            'filter_by_term' => _x('Hide the category filter', 'admin-text', 'site-reviews-filters'),
            'filter_by_rating' => _x('Hide the rating filter', 'admin-text', 'site-reviews-filters'),
            'search_for' => _x('Hide the search bar', 'admin-text', 'site-reviews-filters'),
            'sort_by' => _x('Hide the sort by', 'admin-text', 'site-reviews-filters'),
        ];
    }

    protected function isHidden(): bool
    {
        return count($this->args['hide']) === count(glsr(FilteredDefaults::class)->defaults());
    }
}
