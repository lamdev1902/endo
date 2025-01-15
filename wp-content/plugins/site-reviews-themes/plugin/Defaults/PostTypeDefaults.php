<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Controllers\RestController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class PostTypeDefaults extends DefaultsAbstract
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'capability_type' => Application::POST_TYPE,
            'exclude_from_search' => true,
            'has_archive' => false,
            'hierarchical' => false,
            'labels' => [],
            'map_meta_cap' => true,
            'menu_icon' => 'dashicons-star-half',
            'public' => false,
            'query_var' => true,
            'rest_base' => 'themes',
            'rest_controller_class' => RestController::class,
            'rewrite' => ['with_front' => false],
            'show_in_menu' => 'edit.php?post_type='.glsr()->post_type,
            'show_in_rest' => true,
            'show_ui' => true,
            'supports' => ['title'], // @todo add 'revisions'
            'taxonomies' => [],
        ];
    }
}
