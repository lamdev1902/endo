<?php
/**
 * Customer follow-up email sent after purchase with a reminder to write a review.
 *
 * This template can be overridden by copying it to yourtheme/site-reviews-notifications/woocommerce/review-reminder-html.php.
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

/*
 * Executes the e-mail header.
 *
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email);

/*
 * Show email content - this is set in the email's settings.
 */
echo wp_kses_post(wpautop(wptexturize($content)));

?>

<p>
    <?php
        printf(esc_html_x('Here are the details of your order placed on %s:', 'Order date', 'site-reviews-notifications'),
            esc_html(wc_format_datetime($order->get_date_created()))
        );
?>
</p>

<h2>
    <?php
    echo wp_kses_post(sprintf(_x('[Order #%s]', 'Order ID', 'site-reviews-notifications').' (<time datetime="%s">%s</time>)',
        $order->get_order_number(),
        $order->get_date_created()->format('c'),
        wc_format_datetime($order->get_date_created())
    ));
?>
</h2>

<div style="margin-bottom: 40px;">
    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
        <tbody>
        <?php

    foreach ($order->get_items() as $item_id => $item) {

        $product = $item->get_product();
        $image = '';

        if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
            continue;
        }

        if (is_object($product)) {
            $image = $product->get_image($image_size);
        }

        ?>
            <tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)); ?>">
                <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
                <?php
                    // Product image.
                    echo wp_kses_post(apply_filters('woocommerce_order_item_thumbnail', $image, $item));
                    // Product name.
                    echo wp_kses_post(apply_filters('woocommerce_order_item_name', $item->get_name(), $item, false));
                    // allow other plugins to add additional product information here.
                    do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text);
                    // Product meta
                    wc_display_item_meta($item, [
                        'label_before' => '<strong class="wc-item-meta-label" style="float: '.esc_attr($text_align).'; margin-'.esc_attr($margin_side).': .25em; clear: both">',
                    ]);
                    // allow other plugins to add additional product information here.
                    do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text);
                ?>
                </td>
                <td class="td" style="text-align:<?php echo esc_attr($text_align); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
                    <?php
                        $language = get_post_meta($order->get_id(), 'wpml_language', true);
                        $permalink = apply_filters('wpml_permalink', $product->get_permalink(), $language); 
                    ?>
                    <a href="<?php echo $permalink; ?>#reviews"><?php _e('Rate product', 'site-reviews-notifications'); ?></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php

/**
 * Executes the email footer.
 *
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
