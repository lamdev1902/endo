<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Columns;

class SlugColumn extends Column
{
    public function build(string $value = ''): string
    {
        return get_post($this->postId)->post_name;
    }
}
