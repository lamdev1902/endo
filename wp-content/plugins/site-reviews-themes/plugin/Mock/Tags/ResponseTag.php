<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

use GeminiLabs\SiteReviews\Modules\Html\Builder;

class ResponseTag extends Tag
{
    protected function handle(): string
    {
        $title = sprintf(__('Response from %s', 'site-reviews-themes'), get_bloginfo('name'));
        $response = glsr(Builder::class)->div([
            'class' => 'glsr-review-response-inner',
            'text' => sprintf('<p><strong>%s</strong></p>%s', $title, wpautop($this->value())),
        ]);
        return $response;
    }

    protected function value(): string
    {
        return 'Hi Jane, thank you for the wonderful review and for taking the time to share your feedback with us. We are so pleased you and your dog are enjoying the Dog Park. We hope to see you again very soon!';
    }
}
