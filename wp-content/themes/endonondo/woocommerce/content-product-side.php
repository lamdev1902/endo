<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
  return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
  <div class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
    <div class="ht-prod-thumb">
      <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
        <?php woocommerce_template_loop_product_thumbnail() ?>
      </a>
    </div>
    <div class="ht-prod-info">
      <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
        <?php do_action( 'woocommerce_shop_loop_item_title' ); ?>
      </a>
      <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
      <p>Upgrade to Subscription and Save 15%</p>
      <div class="side-action-buy">
        <a href="?add-to-cart=<?php echo get_the_ID() ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo get_the_ID() ?>" data-product_sku="" aria-label="Add to cart: “<?php the_title() ?>”" rel="nofollow" data-success_message="“<?php the_title() ?>” has been added to your cart">Add</a>
      </div>
    </div>
  </div>
</li>
