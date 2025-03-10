<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); 
$shop_page_url = get_permalink( wc_get_page_id ( 'shop' ) );
?>
<style>
  .cart-prod-info>a {
    font-size: 15px !important;
    font-weight: 600 !important;
    line-height: 20px !important;
  }

  .woocommerce .quantity .qty {
    padding-left: 8px;
  }

  .woocommerce table.shop_table td {
    list-style: 26px !important;
  }

  .woocommerce-cart .wc-proceed-to-checkout a.checkout-button {
    font-size: 16px !important;
    font-style: normal !important;
    font-family: Inter !important;
    font-weight: 500 !important;
    line-height: 24px !important;
    text-align: center !important;
    color: #FFF !important;
  }

  tr.woocommerce-cart-form__cart-item.cart_item td {
    vertical-align: top;
  }

  @media(max-width: 767px) {
    .woocommerce .quantity .qty {
      padding-left: 0px;
    }

    .woocommerce-cart .woocommerce table.shop_table td {
	        width: 100% !important;
    }
  }

  .quantity.sold-individually .minus,
  .quantity.sold-individually .plus {
    pointer-events: none;
    opacity: 0.5;
  }

  .quantity .sold-individually {
    pointer-events: none;
    background-color: #f5f5f5;
    text-align: center;
    color: #999;
    border: 1px solid #ddd;
    text-align: center;
    padding-left: 8px;
    padding-right: 8px;
  }

  .cart-prod-info>a {
    margin-bottom: 4px !important;
  }

  .cart-item-attributes {
    margin-bottom: -7px !important;
  }
</style>
<div class="ht-woo-header">
  <h2>Your Cart</h2>
  <a href="<?=$shop_page_url?>">Continue to shopping</a>
</div>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				/**
				 * Filter the product name.
				 *
				 * @since 2.1.0
				 * @param string $product_name Name of the product in the cart.
				 * @param array $cart_item The product in the cart.
				 * @param string $cart_item_key Key for the product in the cart.
				 */
				$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-thumbnail">
              <div class="cart-prod">
                <div class="cart-prod-thumb">
                  <?php
                  $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                  if ( ! $product_permalink ) {
                    echo $thumbnail; // PHPCS: XSS ok.
                  } else {
                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                  }
						?>
          </div>
          <div class="cart-prod-info">
            <?php
            $product_name_clean = $_product->get_title();

            if ( ! $product_permalink ) {
             echo wp_kses_post( $product_name . '&nbsp;' );
           } else {
              /**
               * This filter is documented above.
               *
               * @since 2.1.0
               */
              // echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
              echo wp_kses_post(apply_filters(
                'woocommerce_cart_item_name',
                sprintf('<a href="%s">%s</a>', esc_url($product_permalink), esc_html($product_name_clean)),
                $cart_item,
                $cart_item_key
              ));
            }

            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

            // Meta data.
            echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

            if (!empty($cart_item['variation']) && is_array($cart_item['variation'])) {
              echo '<ul class="cart-item-attributes">';
              foreach ($cart_item['variation'] as $attribute_name => $attribute_value) {
                $clean_attribute_name = str_replace('attribute_', '', $attribute_name);
                $attribute_label = wc_attribute_label($clean_attribute_name, $_product);

                echo '<li><strong>' . esc_html($attribute_label) . ':</strong> ' . esc_html(wc_attribute_label($attribute_value, $_product)) . '</li>';
              }
              echo '</ul>';
            }

            // Backorder notification.
            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
              echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
            }
            ?>

            <?php
            $terms_cat = get_the_terms( $product_id, 'product_cat' );

            if ( ! empty( $terms_cat ) && ! is_wp_error( $terms_cat ) ) {
              ?>
              <div class="cat-item">
                <?php 
                  $weight = $_product->get_weight();
                  $unit   = get_option('woocommerce_weight_unit');
                  $weight_display = !empty($weight) ? $weight . $unit : '';
                  $category_displayed = false;

                  foreach ($terms_cat as $t => $term) {
                      if ($term->name !== 'Uncategorized') {
                          $term_link = get_term_link($term->term_id, 'product_cat');
                          echo '<a href="' . esc_url($term_link) . '">' . esc_html($term->name) . '</a>';
                          if ($t > 0) echo ' | ';
                          $category_displayed = true;
                      }
                  }

                  if ($category_displayed && !empty($weight_display)) {
                      echo ' | <a href="#">' . esc_html($weight_display) . '</a>';
                  }
                ?>
              </div>
            <?php } ?>

            <?php if (wp_is_mobile()): ?>

              <div class="product-quantity-wrap">
                <?php
                if ( $_product->is_sold_individually() ) {
                  $product_quantity = woocommerce_quantity_input(
                    array(
                      'input_name'   => "cart[{$cart_item_key}][qty]",
                      'input_value'  => 1,
                      'max_value'    => 1,
                      'min_value'    => 0,
                      'product_name' => $product_name,
                      'classes'      => array('sold-individually')
                    ),
                    $_product,
                    false
                  );
               } else {
                 $min_quantity = 0;
                 $max_quantity = $_product->get_max_purchase_quantity();

                 $product_quantity = woocommerce_quantity_input(
                  array(
                   'input_name'   => "cart[{$cart_item_key}][qty]",
                   'input_value'  => $cart_item['quantity'],
                   'max_value'    => $max_quantity,
                   'min_value'    => $min_quantity,
                   'product_name' => $product_name,
                 ),
                  $_product,
                  false
                );
               }

            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
            ?>
            <span>
              <?php
                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                  'woocommerce_cart_item_remove_link',
                  sprintf(
                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                    <path d="M7.5 4L8.5 2H12.5L13.5 4" stroke="#151515"/>
                    <path d="M9.1084 13.75H11.8834" stroke="#151515" stroke-linecap="square" stroke-linejoin="round"/>
                    <path d="M8.4165 10.4167H12.5832" stroke="#151515" stroke-linecap="square" stroke-linejoin="round"/>
                    <path d="M4.5 7L5.5 18H15.5L16.5 7M2.5 5L10.5 4.5L18.5 5" stroke="#151515"/>
                    </svg></a>',
                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                    /* translators: %s is the product name */
                    esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                    esc_attr( $product_id ),
                    esc_attr( $_product->get_sku() )
                  ),
                  $cart_item_key
                );
                ?>
              </span>
            </div>

            <?php
                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                ?>

              <?php endif;?>

            </div>

          </div>
        </td>

        <?php if (!wp_is_mobile()) : ?>

        <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
          <div class="product-quantity-wrap">
            <?php
            if ( $_product->is_sold_individually() ) {
              $product_quantity = woocommerce_quantity_input(
                array(
                  'input_name'   => "cart[{$cart_item_key}][qty]",
                  'input_value'  => 1,
                  'max_value'    => 1,
                  'min_value'    => 0,
                  'product_name' => $product_name,
                  'classes'      => array('sold-individually')
                ),
                $_product,
                false
              );
           } else {
             $min_quantity = 0;
             $max_quantity = $_product->get_max_purchase_quantity();
             $product_quantity = woocommerce_quantity_input(
              array(
               'input_name'   => "cart[{$cart_item_key}][qty]",
               'input_value'  => $cart_item['quantity'],
               'max_value'    => $max_quantity,
               'min_value'    => $min_quantity,
               'product_name' => $product_name,
             ),
              $_product,
              false
            );
           }

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
						?>
            <span>
              <?php
                echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                  'woocommerce_cart_item_remove_link',
                  sprintf(
                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                    <path d="M7.5 4L8.5 2H12.5L13.5 4" stroke="#151515"/>
                    <path d="M9.1084 13.75H11.8834" stroke="#151515" stroke-linecap="square" stroke-linejoin="round"/>
                    <path d="M8.4165 10.4167H12.5832" stroke="#151515" stroke-linecap="square" stroke-linejoin="round"/>
                    <path d="M4.5 7L5.5 18H15.5L16.5 7M2.5 5L10.5 4.5L18.5 5" stroke="#151515"/>
                    </svg></a>',
                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                    /* translators: %s is the product name */
                    esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                    esc_attr( $product_id ),
                    esc_attr( $_product->get_sku() )
                  ),
                  $cart_item_key
                );
                ?>
              </span>
            </div>
          </td>

          <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
           <?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
               ?>
             </td>
                  <?php endif; ?>

           </tr>
           <?php
         }
       }
       ?>


       <?php do_action( 'woocommerce_cart_contents' ); ?>

       <tr>
        <td colspan="6" class="actions" style="display: none">

         <?php if ( wc_coupons_enabled() ) { ?>
          <div class="coupon">
           <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
           <?php do_action( 'woocommerce_cart_coupon' ); ?>
         </div>
       <?php } ?>

       <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

       <?php do_action( 'woocommerce_cart_actions' ); ?>

       <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
     </td>
   </tr>

   <?php do_action( 'woocommerce_after_cart_contents' ); ?>
 </tbody>
</table>
<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
   ?>
 </div>

 <?php do_action( 'woocommerce_after_cart' ); ?>

 <script type="text/javascript">
   jQuery( function( $ ) {
  let timeout;
  $('.woocommerce').on( 'click', '.plus.button, .minus.button', function(){
    if ( timeout !== undefined ) {
      clearTimeout( timeout );
    }
    timeout = setTimeout(function() {
      $("[name='update_cart']").trigger("click");
    }, 500 );
  });

  jQuery(function($) {
    timeout = setTimeout(function() {
      $('.sold-individually').closest('.quantity').find('.minus').css('visibility', 'hidden');
      $('.sold-individually').closest('.quantity').find('.plus').css('visibility', 'hidden');
    }, 100);
  });
} );
 </script>
