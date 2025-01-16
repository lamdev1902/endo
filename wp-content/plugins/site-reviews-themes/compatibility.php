<?php

use GeminiLabs\SiteReviews\Addon\Themes\Application;

defined('WPINC') || die;

/**
 * Remove the "ACF" metabox from review themes
 * @return void
 * @see https://www.advancedcustomfields.com
 */
add_action('add_meta_boxes_site-review-theme', function () {
    if (function_exists('acf_get_instance')) {
        remove_action('edit_form_after_title', [acf_get_instance('ACF_Form_Post'), 'edit_form_after_title']);
    }
});

/**
 * Remove the Oxygen Builder metabox from review forms
 * @see https://oxygenbuilder.com
 */
add_action('plugins_loaded', function () {
    global $ct_ignore_post_types;
    if (!empty($ct_ignore_post_types)) {
        $ct_ignore_post_types[] = Application::POST_TYPE;
        add_filter('pre_option_oxygen_vsb_ignore_post_type_'.Application::POST_TYPE, '__return_true');
    }
});

/**
 * Remove the "Launch Thrive Architect" button from review themes
 * @return array
 * @see https://thrivethemes.com/architect/
 */
add_filter('tcb_post_types', function ($blacklist) {
    $blacklist[] = \GeminiLabs\SiteReviews\Addon\Themes\Application::POST_TYPE;
    return $blacklist;
});

/**
 * Add human-readable capability names
 * @return void
 * @see https://wordpress.org/plugins/members/
 */
add_action('members_register_caps', function () {
    $labels = [
        'create_site-review-themes' => _x('Create Review Themes', 'admin-text', 'site-reviews-themes'),
        'delete_others_site-review-themes' => _x("Delete Others' Review Themes", 'admin-text', 'site-reviews-themes'),
        'delete_site-review-themes' => _x('Delete Review Themes', 'admin-text', 'site-reviews-themes'),
        'delete_private_site-review-themes' => _x('Delete Private Review Themes', 'admin-text', 'site-reviews-themes'),
        'delete_published_site-review-themes' => _x('Delete Published Review Themes', 'admin-text', 'site-reviews-themes'),
        'edit_others_site-review-themes' => _x("Edit Others' Review Themes", 'admin-text', 'site-reviews-themes'),
        'edit_site-review-themes' => _x('Edit Review Themes', 'admin-text', 'site-reviews-themes'),
        'edit_private_site-review-themes' => _x('Edit Private Review Themes', 'admin-text', 'site-reviews-themes'),
        'edit_published_site-review-themes' => _x('Edit Published Review Themes', 'admin-text', 'site-reviews-themes'),
        'publish_site-review-themes' => _x('Publish Review Themes', 'admin-text', 'site-reviews-themes'),
        'read_private_site-review-themes' => _x('Read Private Review Themes', 'admin-text', 'site-reviews-themes'),
    ];
    array_walk($labels, function ($label, $capability) {
        members_register_cap($capability, ['label' => $label]);
    });
});
