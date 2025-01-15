<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Partials;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Style;
use GeminiLabs\SiteReviews\Addon\Themes\ThemeSettings;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Defaults\StarRatingDefaults;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\Partials\StarRating as StarRatingPartial;

class StarRating extends StarRatingPartial
{
    protected Arguments $data;
    protected Arguments $theme;

    public function build(array $args = []): string
    {
        $this->data = glsr()->args(
            glsr(StarRatingDefaults::class)->restrict($args)
        );
        $this->theme = glsr()->args(
            glsr(ThemeSettings::class)
                ->themeId(Arr::getAs('int', $args, 'args.theme'))
                ->get('design.rating')
        );
        return glsr(Builder::class)->div([
            'aria-label' => $this->label(),
            'class' => 'glsr-themed-rating',
            'data-rating' => $this->data->rating,
            'data-reviews' => $this->data->reviews,
            'data-stars' => $this->theme->get('rating_image', 'rating-star'),
            'style' => $this->style(),
            'text' => $this->stars(),
        ]);
    }

    protected function stars(): string
    {
        $types = [ // order is intentional
            'full' => $this->data->num_full,
            'half' => $this->data->num_half,
            'empty' => $this->data->num_empty,
        ];
        $results = [];
        foreach ($types as $type => $repeat) {
            $template = glsr(Builder::class)->span([
                'aria-hidden' => 'true',
                'class' => "glsr-rating-level glsr-rating-{$type}",
                'text' => $this->svg((int) $this->data->rating),
            ]);
            $results[] = str_repeat($template, $repeat);
        }
        return implode('', $results);
    }

    protected function style(): string
    {
        $themeId = Arr::getAs('int', $this->data->args, 'theme');
        $imageName = $this->theme->get('rating_image', 'rating-star');
        $style = glsr(Style::class)->themeId($themeId);
        if ('default' === $imageName || str_starts_with($imageName, 'rating-emoji')) {
            return $style->only([
                '--gl-rating-size',
            ])->toString();
        }
        return $style->only([
            '--gl-rating-color-0', '--gl-rating-color-1', '--gl-rating-color-2',
            '--gl-rating-color-3', '--gl-rating-color-4', '--gl-rating-color-5',
            '--gl-rating-size',
        ])->toString();
    }

    protected function svg(int $rating): string
    {
        $imageName = $this->theme->get('rating_image', 'rating-star');
        if ('default' === $imageName) {
            return '';
        }
        $fileName = str_starts_with($imageName, 'rating-emoji')
            ? sprintf('%s-%d.svg', $imageName, $rating)
            : sprintf('%s.svg', $imageName);
        $filePath = glsr(Application::class)->path('assets/images/rating/'.$fileName);
        if (!file_exists($filePath)) {
            return '';
        }
        $image = file_get_contents($filePath);
        $image = str_replace('gl-gradient-', sprintf('gl-%s', Str::random()), $image);
        return $image;
    }
}
