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

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || ! $product->is_visible()) {
  return;
}
?>
<li <?php wc_product_class('', $product); ?>>
  <div class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
    <div class="ht-prod-thumb">
      <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
        <?php woocommerce_template_loop_product_thumbnail() ?>
      </a>
      <div class="pstatus-absoulte">
        <?php echo display_percentage_on_sale_badge($html, $post, $product)
        ?>
        <?php if ($product->is_featured()) { ?>
          <div class="pstatus pstatus-bsell">BEST SELLER</div>
        <?php } ?>
      </div>
      <div class="add_to_cart_absolute" href="<?php the_permalink() ?>">
        <a href="?add-to-cart=<?php echo get_the_ID() ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo get_the_ID() ?>" data-product_sku="" aria-label="Add to cart: “<?php the_title() ?>”" rel="nofollow" data-success_message="“<?php the_title() ?>” has been added to your cart">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M17.25 22C17.9404 22 18.5 21.4404 18.5 20.75C18.5 20.0596 17.9404 19.5 17.25 19.5C16.5596 19.5 16 20.0596 16 20.75C16 21.4404 16.5596 22 17.25 22Z" fill="#151515" />
            <path d="M9.25 22C9.94036 22 10.5 21.4404 10.5 20.75C10.5 20.0596 9.94036 19.5 9.25 19.5C8.55964 19.5 8 20.0596 8 20.75C8 21.4404 8.55964 22 9.25 22Z" fill="#151515" />
            <path d="M10 9L16 9" stroke="#151515" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="round" />
            <path d="M13 12L13 6" stroke="#151515" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="square" stroke-linejoin="round" />
            <path d="M1.5 2H5.5L7.5 17H19L21 7" stroke="#151515" stroke-width="1.5" />
          </svg>
        </a>
      </div>
    </div>
    <div class="ht-prod-info">
      <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
        <?php do_action('woocommerce_shop_loop_item_title'); ?>
      </a>
      <?php do_action('woocommerce_after_shop_loop_item_title'); ?>
    </div>

  </div>
</li>