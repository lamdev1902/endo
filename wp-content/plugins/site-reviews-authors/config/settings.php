<?php

if (!function_exists('get_editable_roles')) {
    require_once ABSPATH.'wp-admin/includes/user.php';
}

$roles = array_map('translate_user_role', wp_list_pluck(get_editable_roles(), 'name'));
ksort($roles);

return [
    'settings.addons.authors.delete_reviews' => [
        'class' => 'regular-text',
        'default' => 'no',
        'label' => _x('Allow Review Deletion', 'admin-text', 'site-reviews-authors'),
        'sanitizer' => 'text',
        'tooltip' => _x('This will allow review authors to delete their reviews on the frontend.', 'admin-text', 'site-reviews-authors'),
        'type' => 'yes_no',
    ],
    'settings.addons.authors.delete_roles' => [
        'class' => 'regular-grid',
        'default' => ['administrator', 'author', 'contributor', 'editor'],
        'depends_on' => [
            'settings.addons.authors.delete_reviews' => ['yes'],
        ],
        'label' => _x('Review Deletion Roles', 'admin-text', 'site-reviews-authors'),
        'options' => $roles,
        'sanitizer' => 'array-string',
        'tooltip' => _x('Choose which user roles are allowed to delete reviews on the frontend.', 'admin-text', 'site-reviews-authors'),
        'type' => 'checkbox',
    ],
    'settings.addons.authors.edit_reviews' => [
        'class' => 'regular-text',
        'default' => 'yes',
        'label' => _x('Allow Review Editing', 'admin-text', 'site-reviews-authors'),
        'sanitizer' => 'text',
        'tooltip' => _x('This will allow review authors to edit their reviews on the frontend.', 'admin-text', 'site-reviews-authors'),
        'type' => 'yes_no',
    ],
    'settings.addons.authors.roles' => [
        'class' => 'regular-grid',
        'default' => ['administrator', 'author', 'contributor', 'editor'],
        'depends_on' => [
            'settings.addons.authors.edit_reviews' => ['yes'],
        ],
        'label' => _x('Review Editing Roles', 'admin-text', 'site-reviews-authors'),
        'options' => $roles,
        'sanitizer' => 'array-string',
        'tooltip' => _x('Choose which user roles are allowed to edit reviews on the frontend.', 'admin-text', 'site-reviews-authors'),
        'type' => 'checkbox',
    ],
    'settings.addons.authors.respond_to_reviews' => [
        'class' => 'regular-text',
        'default' => 'no',
        'label' => _x('Allow Review Responses', 'admin-text', 'site-reviews-authors'),
        'sanitizer' => 'text',
        'tooltip' => _x('This will allow users with specific roles to respond to reviews on the frontend.', 'admin-text', 'site-reviews-authors'),
        'type' => 'yes_no',
    ],
    'settings.addons.authors.respond_to_roles' => [
        'class' => 'regular-grid',
        'default' => ['administrator'],
        'depends_on' => [
            'settings.addons.authors.respond_to_reviews' => ['yes'],
        ],
        'label' => _x('Review Responses Roles', 'admin-text', 'site-reviews-authors'),
        'options' => $roles,
        'sanitizer' => 'array-string',
        'tooltip' => _x('Choose which user roles are allowed to respond to reviews on the frontend.', 'admin-text', 'site-reviews-authors'),
        'type' => 'checkbox',
    ],
];
