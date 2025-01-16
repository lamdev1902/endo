<?php

defined('WPINC') || die;

/**
 * Load the WPForms stylesheet when using the WPForms plugin style
 * @param string $template
 * @return string
 * @see https://wordpress.org/plugins/wpforms-lite/
 */
add_filter('site-reviews-filters/build/template/filters-form', function ($template) {
    if ('wpforms' === glsr_get_option('general.style')) {
        add_filter('wpforms_global_assets', '__return_true');
    }
    return $template;
});
