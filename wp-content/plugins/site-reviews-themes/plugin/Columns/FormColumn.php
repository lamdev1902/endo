<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Columns;

use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class FormColumn extends Column
{
    public function build(string $value = ''): string
    {
        $formId = Cast::toInt(get_post_meta($this->postId, '_form', true));
        $form = get_post($formId);
        if ($formId === $form->ID) {
            $title = get_the_title($form->ID);
            if (empty(trim($title))) {
                $title = $form->post_name ?: $form->ID;
            }
            return glsr(Builder::class)->a([
                'href' => get_edit_post_link($form),
                'text' => $title,
            ]);
        }
        return '&mdash;';
    }
}
