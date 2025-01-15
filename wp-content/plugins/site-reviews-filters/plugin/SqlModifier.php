<?php

namespace GeminiLabs\SiteReviews\Addon\Filters;

use GeminiLabs\SiteReviews\Addon\Filters\Defaults\FilteredDefaults;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Rating;

class SqlModifier
{
    /** @var array */
    public $values;

    public function modify(array $values): array
    {
        $this->values = $values;
        foreach (glsr(FilteredDefaults::class)->merge() as $parameter => $value) {
            if (!empty($parameter) && $this->validate($parameter, $value)) {
                $this->buildFragment($parameter, $value);
            }
        }
        return $this->values;
    }

    protected function hook($for, $parameter): string
    {
        $slug = Str::dashCase((new \ReflectionClass($this))->getShortName());
        return sprintf('%s/%s/%s', $slug, $for, $parameter);
    }

    /**
     * @param int|string $value
     */
    protected function buildFragment(string $parameter, $value): void
    {
        $key = "filters/{$parameter}";
        $method = Helper::buildMethodName('build', $parameter);
        if (method_exists($this, $method)) {
            call_user_func([$this, $method], $key, $value);
            return;
        }
        // e.g. site-reviews-filters/sql-join/build/filter_by_media
        glsr(Application::class)->action($this->hook('build', $parameter), $value, $key, $this);
    }

    /**
     * @param int|string $value
     */
    protected function validate(string $parameter, $value = ''): bool
    {
        $method = Helper::buildMethodName('validate', $parameter);
        if (empty($value)) {
            return false;
        }
        if (method_exists($this, $method) && !call_user_func([$this, $method], $value)) {
            return false;
        }
        // e.g. site-reviews-filters/sql-join/validate/filter_by_media
        return glsr(Application::class)->filterBool($this->hook('validate', $parameter), true, $value, $parameter, $this);
    }

    /**
     * @param int|string $value
     */
    protected function validateFilterByRating($value): bool
    {
        $min = glsr()->constant('MIN_RATING', Rating::class);
        $max = glsr()->constant('MAX_RATING', Rating::class);
        $allowedValues = array_merge(range($min, $max), ['critical', 'positive']);
        return in_array($value, $allowedValues);
    }
}
