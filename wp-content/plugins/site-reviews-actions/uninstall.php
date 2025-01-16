<?php defined('WP_UNINSTALL_PLUGIN') || die;

global $wpdb;

function glsr_uninstall() {
    $settings = get_option('site_reviews_v7');
    $uninstall = isset($settings['settings']['general']['delete_data_on_uninstall'])
        ? $settings['settings']['general']['delete_data_on_uninstall']
        : '';
    if ('all' === $uninstall) {
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}glsr_actions_log");
    }
}

if (!is_multisite()) {
    glsr_uninstall();
    return;
}

if (!function_exists('get_sites')) {
    $siteIds = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
} else {
    $siteIds = get_sites(['count' => false, 'fields' => 'ids']);
}

foreach ($siteIds as $siteId) {
    switch_to_blog($siteId);
    glsr_uninstall();
    restore_current_blog();
}
