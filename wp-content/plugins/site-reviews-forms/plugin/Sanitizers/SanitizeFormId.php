<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Sanitizers;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Sanitizers\IntSanitizer;

class SanitizeFormId extends IntSanitizer
{
    public function run(): int
    {
        return $this->value();
    }

    protected function value(): int
    {
        if (!is_numeric($this->value) && is_string($this->value)) {
            $postTypeSlug = Str::prefix($this->value, Application::POST_TYPE.':');
            $this->value = Helper::getPostId($postTypeSlug);
        }
        $this->value = Cast::toInt($this->value);
        if (Application::POST_TYPE !== get_post_type($this->value)) {
            $this->value = 0;
        }
        return $this->value;
    }
}
