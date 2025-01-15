<?php
/* Template Name: Woo Shop - Keep Out */
get_header();
?>
<style>
    #breadcrumbs .breadcrumb-separator {
        color: #aaa;
        font-size: 0.8em;
        margin: 0 5px;
        vertical-align: middle;
    }
</style>
<main id="main" class="product-detail-main woocommerce">
  <div class="product-detail-wrap">
    <div class="link-page">
      <div class="container">
        <?php
        // if ( function_exists('yoast_breadcrumb') ) {
        //   yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        // }
        ?>

        <nav aria-label="breadcrumb" id="breadcrumbs">
          <a href="<?php echo esc_url(home_url()); ?>">Home</a>
          <span class="breadcrumb-separator"> | </span>
          <?php
          if (is_shop()) {
            echo '<strong>Shop</strong>';
          } elseif (is_product_category()) {
            $current_category = get_queried_object();
            echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">Shop</a>';
            echo '<span class="breadcrumb-separator"> | </span>';
            echo '<strong>' . esc_html($current_category->name) . '</strong>';
          } elseif (is_product()) {
            $product_categories = get_the_terms(get_the_ID(), 'product_cat');
            if ($product_categories && !is_wp_error($product_categories)) {
              $main_category = $product_categories[0];
              echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">Shop</a>';
              echo '<span class="breadcrumb-separator"> | </span>';
              echo '<a href="' . esc_url(get_term_link($main_category)) . '">' . esc_html($main_category->name) . '</a>';
              echo '<span class="breadcrumb-separator"> | </span>';
            }
            echo '<strong>' . get_the_title() . '</strong>';
          } elseif (is_checkout()) {
            echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">Shop</a>';
            echo '<span class="breadcrumb-separator"> | </span>';
            echo '<strong>Checkout</strong>';
          } elseif (is_cart()) {
            echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">Shop</a>';
            echo '<span class="breadcrumb-separator"> | </span>';
            echo '<strong>Cart</strong>';
          } else {
            echo '<strong>' . get_the_title() . '</strong>';
          }
          ?>
        </nav>

      </div>
    </div>
    <div class="container">
      <?php the_content() ?>
    </div>
    <div class="random-product">
      <div class="container">
        <h2 class="random-product-title">You may also like</h2>
        <?php
        $products_query = new WP_Query(array('post_type' => 'product', 'post_status' => 'publish', 'orderby' => 'rand', 'posts_per_page' => 3,));
        ?>
        <?php
        if ($products_query->have_posts()) :
        ?>
          <ul id="load-posts" class="products columns-3">
            <?php
            while ($products_query->have_posts()) : $products_query->the_post();
              wc_get_template_part('content', 'product-related');
            ?>
            <?php
            endwhile;
            ?>
          </ul>
        <?php
          wp_reset_query();
        endif; ?>
      </div>
    </div>
  </div>
</main>

<style>
  #breadcrumbs span span {
    margin: 0 4px;
  }

  .ht-woo-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 20px 0;
  }

  .ht-woo-header>h2 {
    font-size: 24px;
    line-height: normal;
    font-weight: bold;
    margin: 0;
  }

  .ht-woo-header a {
    color: #404040;
    font-size: 14px;
    text-decoration: underline;
  }

  #add_payment_method table.cart img,
  .woocommerce-cart table.cart img,
  .woocommerce-checkout table.cart img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    box-shadow: none;
  }

  .cart-prod {
    display: flex;
  }

  .cart-prod-info {
    flex: 1;
    padding-left: 15px;
  }

  .cart-prod-info>a {
    display: block;
    font-size: 16px;
    color: #151515;
    font-style: normal;
    font-weight: 700;
    margin-bottom: 18px;
  }

  .cart-prod-info .cat-item a {
    font-size: 12px;
    color: #151515;
  }

  #add_payment_method table.cart td,
  #add_payment_method table.cart th,
  .woocommerce-cart table.cart td,
  .woocommerce-cart table.cart th,
  .woocommerce-checkout table.cart td,
  .woocommerce-checkout table.cart th {
    padding: 0;
    border: none;
  }

  .woocommerce-cart table.cart {
    border: none;
  }

  .woocommerce-cart table.cart th {
    font-size: 12px;
    font-weight: 400;
  }

  tr.woocommerce-cart-form__cart-item.cart_item td {
    padding: 20px 0;
  }

  .product-quantity-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 32px;
    gap: 12px;
  }

  .product-quantity-wrap .quantity {
    display: flex;
    align-items: center;
    border: 1px solid #BBB;
  }

  .product-quantity-wrap .quantity .button {
    font-size: 20px;
    font-weight: 400;
    background: transparent !important;
    padding: 6px 15px;
  }

  .product-quantity-wrap .quantity input[type="number"] {
    font-size: 16px;
    border: none;
    background: transparent !important;
  }

  .woocommerce a.remove {
    background: transparent !important;
  }

  .woocommerce a.remove:hover svg path {
    stroke: red;
  }

  th.product-quantity {
    text-align: center;
  }

  th.product-subtotal {
    text-align: right;
  }

  td.product-subtotal {
    text-align: right;
    font-size: 16px;
    font-weight: bold;
  }

  .woocommerce-error,
  .woocommerce-info,
  .woocommerce-message {
    padding: 12px;
    padding-left: 50px;
    display: flex;
    align-items: center;
    margin: 20px 0;
  }

  .woocommerce-message {
    border-top-color: #FF5757;
  }

  .woocommerce-message::before {
    color: #FF5757;
    top: 50%;
    transform: translateY(-50%);
  }

  .woocommerce .woocommerce-message .button {
    margin-left: auto;
    background: #000 !important;
    color: #fff !important;
  }

  .cart-collaterals {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #E1E3E5;
  }

  .woocommerce .cart-collaterals .cart_totals,
  .woocommerce-page .cart-collaterals .cart_totals {
    float: right;
    width: 300px;
  }

  .woocommerce table.shop_table {
    border: none !important;
    border-radius: 0;
  }

  .woocommerce-cart .cart-collaterals .cart_totals table {
    margin: 0;
  }

  .woocommerce-cart .cart-collaterals .cart_totals table td {
    text-align: right;
    padding-left: 0;
    padding-right: 0;
    padding-top: 0;
    border: none;
    padding-bottom: 16px;
  }

  .woocommerce-cart .cart-collaterals .cart_totals table td span.amount {
    font-size: 20px;
    font-weight: bold;
    color: #151515;
    margin-left: 10px;
  }

  .woocommerce-cart .cart-collaterals .cart_totals table td.taxes {
    font-size: 12px;
  }

  .woocommerce-cart .cart-collaterals .cart_totals table td.subtotal-total {}

  .woocommerce-cart .wc-proceed-to-checkout a.checkout-button {
    background: #151515;
    font-size: 16px;
    border-radius: 0;
    color: #fff;
    margin: 0;
  }

  .woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover {
    background: #FF5757;
  }

  .woocommerce-cart .wc-proceed-to-checkout {
    padding: 0;
  }

  button[name="update_cart"] {
    border-radius: 0;
    font-size: 14px;
  }

  .random-product {
    margin-top: 20px;
    background: #F8F8F8;
    padding: 20px 0;
  }

  .random-product .random-product-title {
    font-size: 24px;
    font-weight: bold;
    line-height: normal;
    margin-bottom: 20px;
  }

  @media (min-width: 0px) and (max-width: 480px) {
    .link-page {
      padding-bottom: 16px;
      border-bottom: 1px solid #BBB;
    }

    .woocommerce-page table.cart .product-thumbnail {
      display: block !important;
      background: transparent !important;
      text-align: left !important;
    }

    .woocommerce table.shop_table_responsive tr td::before,
    .woocommerce-page table.shop_table_responsive tr td::before {
      display: none;
    }

    .product-quantity-wrap {
      justify-content: flex-start;
    }

    .cart-prod-info {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .cart-prod-info>a {
      margin: 0;
    }

    .cart-prod-info .amount {
      font-weight: bold;
    }

    .woocommerce .cart-collaterals .cart_totals,
    .woocommerce-page .cart-collaterals .cart_totals {
      width: 100% !important;
    }

  }

  }
</style>

<script type="text/javascript">
  jQuery(function($) {
    let timeout;
    $('.woocommerce').on('change', 'input.qty', function() {
      if (timeout !== undefined) {
        clearTimeout(timeout);
      }
      timeout = setTimeout(function() {
        $("[name='update_cart']").trigger("click"); // trigger cart update
      }, 1000); // 1 second delay, half a second (500) seems comfortable too
    });
  });
</script>

<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/woocommerce/css/product.css' ?>">
<?php
get_footer();
