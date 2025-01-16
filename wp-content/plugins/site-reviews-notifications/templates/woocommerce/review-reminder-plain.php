<?php
/**
 * Customer follow-up email sent after purchase with a reminder to write a review (plain text)
 *
 * This template can be overridden by copying it to yourtheme/site-reviews-notifications/woocommerce/review-reminder-plain.php.
 *
 * HOWEVER, on occasion the Site Reviews Notifications add-on will need to update 
 * template files and you (the theme developer) will need to copy the new files 
 * to your theme to maintain compatibility. We try to do this as little as 
 * possible, but it does happen. When this occurs the version of the template 
 * file will be bumped and the readme will list any important changes.
 *
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html(wp_strip_all_tags($email_heading));
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/**
 * Show user-defined content - this is set in the email's settings.
 */
echo esc_html(wp_strip_all_tags(wptexturize($content)));
echo "\n\n----------------------------------------\n\n";

echo sprintf(esc_html_x('Here are the details of your order placed on %s:', 'Order date', 'site-reviews-woocommerce'), esc_html(wc_format_datetime($order->get_date_created())))."\n\n";

echo wp_kses_post(wc_strtoupper(sprintf(esc_html_x('[Order #%s]', 'Order ID', 'site-reviews-notifications').' (%s)', $order->get_order_number(), wc_format_datetime($order->get_date_created()))));

foreach ($items as $item_id => $item) {
    if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
        continue;
    }
    $product = $item->get_product();

    // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    echo "\n\n".wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false));

    $language = get_post_meta($order->get_id(), 'wpml_language', true);
    $permalink = apply_filters('wpml_permalink', $product->get_permalink(), $language); 

    echo "\n".$permalink.'#reviews';

    // allow other plugins to add additional product information here.
    do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text);

    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo strip_tags(
        wc_display_item_meta($item, [
            'before' => "\n- ",
            'separator' => "\n- ",
            'after' => '',
            'echo' => false,
            'autop' => false,
        ])
    );

    // allow other plugins to add additional product information here.
    do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text);
}

echo "\n\n----------------------------------------\n\n";

echo wp_kses_post(apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text')));
