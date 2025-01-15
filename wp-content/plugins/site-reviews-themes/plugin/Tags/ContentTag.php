<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Tags;

use GeminiLabs\SiteReviews\Addon\Themes\ThemeSettings;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Text;
use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewContentTag;

class ContentTag extends ReviewContentTag
{
    protected function value(): string
    {
        $settings = glsr(ThemeSettings::class)
            ->themeId($this->args->cast('theme', 'int'))
            ->get('presentation.excerpt');
        $action = Arr::get($settings, 'excerpt_action');
        if ($this->isRaw() || 'disabled' === $action) {
            return Text::text($this->value);
        }
        $length = explode('|', Arr::get($settings, 'excerpt_length'));
        $limit = Cast::toInt(Arr::get($length, 0));
        $splitWords = 'words' === Arr::get($length, 1);
        $excerpt = Text::excerpt($this->value, $limit, $splitWords);
        $pattern = '/(data-trigger=[\'"])\w*([\'"])/u';
        $replace = '$1'.$action.'$2';
        return preg_replace($pattern, $replace, $excerpt);
    }
}
