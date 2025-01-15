<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Columns;

use GeminiLabs\SiteReviews\Helpers\Cast;

class ShortcodeColumn extends Column
{
    public function build(string $value = ''): string
    {
        // $slug = get_post($this->postId)->post_name;
        // $shortcode = sprintf('[%s theme="%s"]', $value, $slug);
        $shortcode = sprintf('[%s theme="%d"]', $value, $this->postId);
        return sprintf('<code data-select-text class="template-tag">%s</code>', $shortcode);
    }
}
