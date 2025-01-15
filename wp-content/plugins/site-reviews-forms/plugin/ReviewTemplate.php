<?php

namespace GeminiLabs\SiteReviews\Addon\Forms;

use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Template;

class ReviewTemplate
{
    public const META_KEY = '_review_template';

    public function normalize(string $template = ''): string
    {
        if (empty($template)) {
            $template = glsr(Template::class)->build('templates/review');
        }
        return wp_kses(trim($template), wp_kses_allowed_html('post')); // clean the HTML first
    }

    public function normalizedTemplate(int $formId): string
    {
        $template = Cast::toString(get_post_meta($formId, static::META_KEY, true));
        return $this->normalize($template);
    }

    public function reservedTags(): array
    {
        $tags = array_keys(glsr_get_review(0)->build()->context);
        sort($tags);
        return $tags;
    }

    public function save(int $formId, string $template): void
    {
        update_post_meta($formId, static::META_KEY, $this->normalize($template));
    }

    /**
     * This returns the raw meta data value.
     */
    public function template(int $formId): string
    {
        return Cast::toString(get_post_meta($formId, static::META_KEY, true));
    }
}
