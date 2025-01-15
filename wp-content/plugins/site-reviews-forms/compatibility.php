<?php

use GeminiLabs\SiteReviews\Addon\Forms\Application;

defined('WPINC') || die;

/**
 * Remove the "ACF" metabox from review forms
 * @return void
 * @see https://www.advancedcustomfields.com
 */
add_action('add_meta_boxes_site-review-form', function () {
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
 * Remove the "Launch Thrive Architect" button from review forms
 * @return array
 * @see https://thrivethemes.com/architect/
 */
add_filter('tcb_post_types', function ($blacklist) {
    $blacklist[] = \GeminiLabs\SiteReviews\Addon\Forms\Application::POST_TYPE;
    return $blacklist;
});

/**
 * Add human-readable capability names
 * @return void
 * @see https://wordpress.org/plugins/members/
 */
add_action('members_register_caps', function () {
    $labels = [
        'create_site-review-forms' => _x('Create Review Forms', 'admin-text', 'site-reviews-forms'),
        'delete_others_site-review-forms' => _x("Delete Others' Review Forms", 'admin-text', 'site-reviews-forms'),
        'delete_site-review-forms' => _x('Delete Review Forms', 'admin-text', 'site-reviews-forms'),
        'delete_private_site-review-forms' => _x('Delete Private Review Forms', 'admin-text', 'site-reviews-forms'),
        'delete_published_site-review-forms' => _x('Delete Published Review Forms', 'admin-text', 'site-reviews-forms'),
        'edit_others_site-review-forms' => _x("Edit Others' Review Forms", 'admin-text', 'site-reviews-forms'),
        'edit_site-review-forms' => _x('Edit Review Forms', 'admin-text', 'site-reviews-forms'),
        'edit_private_site-review-forms' => _x('Edit Private Review Forms', 'admin-text', 'site-reviews-forms'),
        'edit_published_site-review-forms' => _x('Edit Published Review Forms', 'admin-text', 'site-reviews-forms'),
        'publish_site-review-forms' => _x('Publish Review Forms', 'admin-text', 'site-reviews-forms'),
        'read_private_site-review-forms' => _x('Read Private Review Forms', 'admin-text', 'site-reviews-forms'),
    ];
    array_walk($labels, function ($label, $capability) {
        members_register_cap($capability, ['label' => $label]);
    });
});
