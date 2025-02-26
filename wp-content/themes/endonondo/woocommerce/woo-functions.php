<?php
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
//remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
add_action('woocommerce_checkout_before_order_review', 'woocommerce_checkout_payment', 20);
//add_action('woocommerce_review_order_after_cart_contents', 'woocommerce_checkout_coupon_form', 10);
function ht_woocommerce_setup()
{
  add_theme_support('woocommerce');
  remove_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}

add_action('after_setup_theme', 'ht_woocommerce_setup');

// Button buy

add_action('woocommerce_after_add_to_cart_button', 'sb_quickbuy_after_addtocart_button');
function sb_quickbuy_after_addtocart_button()
{
  global $product;
?>
  <style>
    .sb-quickbuy button.single_add_to_cart_button.loading:after {
      display: none;
    }

    .sb-quickbuy button.single_add_to_cart_button.button.alt.loading {
      color: #fff;
      pointer-events: none !important;
    }

    .sb-quickbuy button.ht_buy_now_button {
      position: relative;
      color: rgba(255, 255, 255, 0.05);
    }

    .sb-quickbuy button.ht_buy_now_button:after {
      animation: spin 500ms infinite linear;
      border: 2px solid #fff;
      border-radius: 32px;
      border-right-color: transparent !important;
      border-top-color: transparent !important;
      content: "";
      display: block;
      height: 16px;
      top: 50%;
      margin-top: -8px;
      left: 50%;
      margin-left: -8px;
      position: absolute;
      width: 16px;
    }
  </style>
  <button type="button" class="button ht_buy_now_button" target="_blank">
    <?php _e('Buy it now', 'sb'); ?>
  </button>
  <input type="hidden" name="is_buy_now" class="is_buy_now" value="0" autocomplete="off" />
  <input type="hidden" name="is_checked_buy_now" class="is_buy_now" value="0" autocomplete="off" />
  <script>
    jQuery(document).ready(function() {
      jQuery('.is_buy_now').val('0');
      jQuery('body').on('click', '.ht_buy_now_button', function(e) {
        e.preventDefault();
        var thisParent = jQuery(this).parents('form.cart');
        if (jQuery('.single_add_to_cart_button', thisParent).hasClass('disabled')) {
          jQuery('.single_add_to_cart_button', thisParent).trigger('click');
          return false;
        }
        thisParent.addClass('sb-quickbuy');
        jQuery('.is_buy_now', thisParent).val('1');
        jQuery('.is_checked_buy_now', thisParent).val('1');
        jQuery('.single_add_to_cart_button', thisParent).trigger('click');
        setTimeout(function() {
          window.open("https://www.rho.org/checkout", "_blank")
        }, 1500);

      });
    });
    jQuery(document.body).on('added_to_cart', function(e, fragments, cart_hash, addToCartButton) {
      let thisForm = addToCartButton.closest('.cart');
      let is_buy_now = parseInt(jQuery('.is_buy_now', thisForm).val()) || 0;
      if (is_buy_now === 1 && typeof wc_add_to_cart_params !== "undefined") {
        window.location = wc_add_to_cart_params.cart_url;
      }
    });
  </script>
  <?php
}

//add_filter('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout($redirect_url)
{

  if (!get_theme_mod('ajax_add_to_cart')) {

    if (isset($_REQUEST['is_buy_now']) && $_REQUEST['is_buy_now'] && get_option('woocommerce_cart_redirect_after_add') !== 'yes') {
      $redirect_url = wc_get_checkout_url();
  ?>

      <script type="text/javascript">
        var URL = '<?php echo $redirect_url; ?>';
        window.open(URL, '_blank')
      </script> ;

  <?php
    }
  }
  // return $redirect_url;
}

add_filter('woocommerce_get_script_data', 'sb_woocommerce_get_script_data', 10, 2);
function sb_woocommerce_get_script_data($params, $handle)
{
  if ($handle == 'wc-add-to-cart') {
    $params['cart_url'] = wc_get_checkout_url();
  }
  return $params;
}

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */

function gda_woocommerce_loop_columns()
{
  return 3;
}

add_filter('loop_shop_columns', 'gda_woocommerce_loop_columns');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */

function gda_woocommerce_related_products_args($args)
{
  $defaults = array(
    'posts_per_page' => 3,
    'columns'        => 3,
  );

  $args = wp_parse_args($defaults, $args);
  return $args;
}

add_filter('woocommerce_output_related_products_args', 'gda_woocommerce_related_products_args');

add_filter('woocommerce_format_price_range', 'custom_format_price_range', 10, 3);

function custom_format_price_range($price)
{
  $price = str_replace('&ndash;', '', $price);
  return $price;
}

function gda_woocommerce_products_per_page()
{
  if (wp_is_mobile()) {
    return 6;
  } else {
    return 3;
  }
}
add_filter('loop_shop_per_page', 'gda_woocommerce_products_per_page');

add_filter('woocommerce_pagination_args',  'rocket_woo_pagination');
function rocket_woo_pagination($args)
{

  $args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
  <path d="M18.95 9.2625L13.225 15L18.95 20.7375L17.1875 22.5L9.68745 15L17.1875 7.5L18.95 9.2625Z" fill="#BBBBBB"/>
  </svg>';
  $args['next_text'] = '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
  <path d="M11.05 9.2625L16.775 15L11.05 20.7375L12.8125 22.5L20.3125 15L12.8125 7.5L11.05 9.2625Z" fill="#BBBBBB"/>
  </svg>';

  return $args;
}

// Save per


// function display_percentage_on_sale_badge($post, $product, $html = '')
// {

//   if ($product->is_type('variable')) {
//     $percentages = array();

//     // This will get all the variation prices and loop throughout them
//     $prices = $product->get_variation_prices();

//     foreach ($prices['price'] as $key => $price) {
//       // Only on sale variations
//       if ($prices['regular_price'][$key] !== $price) {
//         // Calculate and set in the array the percentage for each variation on sale
//         $percentages[] = round(100 - (floatval($prices['sale_price'][$key]) / floatval($prices['regular_price'][$key]) * 100));
//       }
//     }
//     // Displays maximum discount value
//     $percentage = max($percentages) . '%';
//   } elseif ($product->is_type('grouped')) {
//     $percentages = array();

//     // This will get all the variation prices and loop throughout them
//     $children_ids = $product->get_children();

//     foreach ($children_ids as $child_id) {
//       $child_product = wc_get_product($child_id);

//       $regular_price = (float) $child_product->get_regular_price();
//       $sale_price    = (float) $child_product->get_sale_price();

//       if ($sale_price != 0 || ! empty($sale_price)) {
//         // Calculate and set in the array the percentage for each child on sale
//         $percentages[] = round(100 - ($sale_price / $regular_price * 100));
//       }
//     }
//     // Displays maximum discount value
//     $percentage = max($percentages) . '%';
//   } else {
//     $regular_price = (float) $product->get_regular_price();
//     $sale_price    = (float) $product->get_sale_price();

//     if ($sale_price != 0 || ! empty($sale_price)) {
//       $percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';
//     } else {
//       return $html;
//     }
//   }
//   return '<div class="pstatus pstatus-save">' . esc_html__('SAVE', 'woocommerce') . ' ' . $percentage . '</div>'; // If needed then change or remove "up to -" text
// }
function display_percentage_on_sale_badge($html = '', $post, $product)
{
    if ($product->is_type('variable')) {
        $percentages = array();
        $prices = $product->get_variation_prices();

        foreach ($prices['price'] as $key => $price) {
            if (!empty($prices['regular_price'][$key]) && !empty($prices['sale_price'][$key]) && $prices['regular_price'][$key] > $prices['sale_price'][$key]) {
                $percentages[] = round(100 - (floatval($prices['sale_price'][$key]) / floatval($prices['regular_price'][$key]) * 100));
            }
        }

        $percentage = !empty($percentages) ? max($percentages) . '%' : null;
    } elseif ($product->is_type('grouped')) {
        $percentages = array();
        $children_ids = $product->get_children();

        foreach ($children_ids as $child_id) {
            $child_product = wc_get_product($child_id);
            $regular_price = (float) $child_product->get_regular_price();
            $sale_price = (float) $child_product->get_sale_price();

            if ($sale_price > 0 && $regular_price > $sale_price) {
                $percentages[] = round(100 - ($sale_price / $regular_price * 100));
            }
        }

        $percentage = !empty($percentages) ? max($percentages) . '%' : null;
    } else {
        $regular_price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();

        if ($sale_price > 0 && $regular_price > $sale_price) {
            $percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';
        } else {
            return $html;
        }
    }

    if (!isset($percentage)) {
        return $html;
    }

    return '<div class="pstatus pstatus-save">' . esc_html__('SAVE', 'woocommerce') . ' ' . $percentage . '</div>';
}

remove_action('woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30);
add_action('woocommerce_external_add_to_cart', 'rei_external_add_to_cart', 30);
function rei_external_add_to_cart()
{

  global $product;

  if (! $product->add_to_cart_url()) {
    return;
  }

  $product_url = $product->add_to_cart_url();
  $button_text = $product->single_add_to_cart_text();

  do_action('woocommerce_before_add_to_cart_button'); ?>
  <p class="cart">
    <a href="<?php echo esc_url($product_url); ?>" target="_blank" rel="nofollow" class="single_add_to_cart_button button alt"><?php echo esc_html($button_text); ?></a>
  </p>
<?php do_action('woocommerce_after_add_to_cart_button');
}

add_filter('woocommerce_single_product_image_thumbnail_html', 'custom_remove_product_link');
function custom_remove_product_link($html)
{
  return strip_tags($html, '<div><img>');
}
