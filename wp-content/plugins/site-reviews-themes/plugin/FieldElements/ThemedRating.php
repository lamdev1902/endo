<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\FieldElements;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\ThemeSettings;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Html\FieldElements\Rating as RatingFieldElement;
use GeminiLabs\SiteReviews\Modules\Rating;

class ThemedRating extends RatingFieldElement
{
    public function build(array $overrideArgs = []): string
    {
        $select = parent::build($overrideArgs);
        $settings = glsr(ThemeSettings::class)->themeId(Cast::toInt($this->field->theme));
        $prebuilt = $this->buildRatingStars($settings);
        return $this->field->builder()->span([
            'class' => 'glsr-star-rating',
            'text' => $select.$prebuilt,
        ]);
    }

    public function required(): array
    {
        $required = parent::required();
        $required['data-options'] = ['prebuilt' => true];
        return $required;
    }

    protected function buildRatingImage(int $rating, ThemeSettings $settings): string
    {
        $imageName = $settings->get('design.rating.rating_image');
        $fileName = str_starts_with($imageName, 'rating-emoji')
            ? sprintf('%s-%d.svg', $imageName, $rating)
            : sprintf('%s.svg', $imageName);
        $image = file_get_contents(glsr(Application::class)->path('assets/images/rating/'.$fileName));
        $image = str_replace('gl-gradient-', sprintf('gl-%s', Str::random()), $image);
        return $image;
    }

    protected function buildRatingStars(ThemeSettings $settings): string
    {
        $ratings = glsr(Rating::class)->emptyArray();
        $stars = [];
        $i = 0;
        foreach ($ratings as $rating => $value) {
            if ($rating > 0) {
                $stars[] = $this->field->builder()->span([
                    'data-index' => $i++,
                    'data-value' => $rating,
                    'text' => $this->buildRatingImage($rating, $settings),
                ]);
            }
        }
        return $this->field->builder()->span([
            'class' => 'glsr-star-rating--stars',
            'data-stars' => $settings->get('design.rating.rating_image'),
            'text' => implode('', $stars),
        ]);
    }
}
