<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Commands;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Defaults\PostTypeDefaults;
use GeminiLabs\SiteReviews\Addon\Themes\Defaults\PostTypeLabelDefaults;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;

class RegisterPostType extends AbstractCommand
{
    public $args;

    public function __construct()
    {
        $this->args = glsr(PostTypeDefaults::class)->merge([
            'labels' => glsr(PostTypeLabelDefaults::class)->defaults(),
        ]);
    }

    public function handle(): void
    {
        $types = get_post_types(['_builtin' => true]);
        if (!in_array(Application::POST_TYPE, $types)) {
            register_post_type(Application::POST_TYPE, $this->args);
        }
    }
}
