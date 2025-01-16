<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Defaults;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class PostTypeLabelDefaults extends DefaultsAbstract
{
    protected function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'add_new' => _x('Add New Form', 'Add New Post (admin-text)', 'site-reviews-forms'),
            'add_new_item' => _x('Add New Form', 'Add New Post (admin-text)', 'site-reviews-forms'),
            'all_items' => _x('All Forms', 'All Posts (admin-text)', 'site-reviews-forms'),
            'archives' => _x('Review Form Archives', 'Post Archives (admin-text)', 'site-reviews-forms'),
            'edit_item' => _x('Edit Review Form', 'Edit Post (admin-text)', 'site-reviews-forms'),
            'insert_into_item' => _x('Insert into Form', 'Insert into Post (admin-text)', 'site-reviews-forms'),
            'name' => _x('Review Forms', 'admin-text', 'site-reviews-forms'),
            'new_item' => _x('New Form', 'New Post (admin-text)', 'site-reviews-forms'),
            'not_found' => _x('No Review Forms found', 'No Posts found (admin-text)', 'site-reviews-forms'),
            'not_found_in_trash' => _x('No Review Forms found in Trash', 'No Posts found in Trash (admin-text)', 'site-reviews-forms'),
            'search_items' => _x('Search Forms', 'Search Posts (admin-text)', 'site-reviews-forms'),
            'singular_name' => _x('Review Form', 'admin-text', 'site-reviews-forms'),
            'uploaded_to_this_item' => _x('Uploaded to this Form', 'Uploaded to this Post (admin-text)', 'site-reviews-forms'),
            'view_item' => _x('View Review Form', 'View Post (admin-text)', 'site-reviews-forms'),
        ];
    }
}
