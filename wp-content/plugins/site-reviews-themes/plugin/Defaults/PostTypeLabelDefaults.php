<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class PostTypeLabelDefaults extends DefaultsAbstract
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'add_new' => _x('Add New Theme', 'Add New Post (admin-text)', 'site-reviews-themes'),
            'add_new_item' => _x('Add New Theme', 'Add New Post (admin-text)', 'site-reviews-themes'),
            'all_items' => _x('All Themes', 'All Posts (admin-text)', 'site-reviews-themes'),
            'archives' => _x('Review Theme Archives', 'Post Archives (admin-text)', 'site-reviews-themes'),
            'edit_item' => _x('Edit Review Theme', 'Edit Post (admin-text)', 'site-reviews-themes'),
            'insert_into_item' => _x('Insert into Theme', 'Insert into Post (admin-text)', 'site-reviews-themes'),
            'name' => _x('Review Themes', 'admin-text', 'site-reviews-themes'),
            'new_item' => _x('New Theme', 'New Post (admin-text)', 'site-reviews-themes'),
            'not_found' => _x('No Review Themes found', 'No Posts found (admin-text)', 'site-reviews-themes'),
            'not_found_in_trash' => _x('No Review Themes found in Trash', 'No Posts found in Trash (admin-text)', 'site-reviews-themes'),
            'search_items' => _x('Search Themes', 'Search Posts (admin-text)', 'site-reviews-themes'),
            'singular_name' => _x('Review Theme', 'admin-text', 'site-reviews-themes'),
            'uploaded_to_this_item' => _x('Uploaded to this Theme', 'Uploaded to this Post (admin-text)', 'site-reviews-themes'),
            'view_item' => _x('View Review Theme', 'View Post (admin-text)', 'site-reviews-themes'),
        ];
    }
}
