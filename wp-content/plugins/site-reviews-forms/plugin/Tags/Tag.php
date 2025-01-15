<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Tags;

use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewTag;

class Tag extends ReviewTag
{
    /** @var \GeminiLabs\SiteReviews\Arguments */
    public $field;

    public function __construct($tag, $field, array $args = [])
    {
        $this->args = glsr()->args($args);
        $this->field = $field;
        $this->tag = $tag;
    }
}
