<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https:
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined('ABSPATH') || exit;

get_header('shop');

$term = get_queried_object();

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */


woocommerce_output_content_wrapper();
?>
<?php

$slider_best_seller_heading = get_field('slider_best_seller_heading', wc_get_page_id('shop'));

$grid_best_seller_image = get_field('grid_best_seller_image', wc_get_page_id('shop'));
$grid_best_seller_content = get_field('grid_best_seller_content', wc_get_page_id('shop'));


$browse_our_categories = get_field('browse_our_categories', wc_get_page_id('shop'));
$new_arrivals = get_field('new_arrivals', wc_get_page_id('shop'));
$new_arrival_img = get_field('new_arrivals_image', wc_get_page_id('shop'));

$banner_image = get_field('banner_image', wc_get_page_id('shop'));
$banner_content = get_field('banner_content', wc_get_page_id('shop'));
$best_seller_top = get_field('best_seller_top', wc_get_page_id('shop'));
$best_seller_left = get_field('best_seller_left', wc_get_page_id('shop'));
$best_seller_left_image = get_field('best_seller_left_image', wc_get_page_id('shop'));
$best_seller_right = get_field('best_seller_right', wc_get_page_id('shop'));

$best_seller_right_image = get_field('best_seller_right_image', wc_get_page_id('shop'));
$top_banner = get_field('top_banner', wc_get_page_id('shop'));
$top_banner_image = get_field('top_banner_image', wc_get_page_id('shop'));

?>
<style>
  .heading {

    text-align: left;
    margin-bottom: 30px;
    padding: 20px;
    background-color: #f9f9f9;
  }

  .heading h5 {
    font-size: 1rem;
    color: #8dc63f;
    margin-bottom: 10px;
    font-weight: normal;
    text-transform: uppercase;
  }

  .heading h2 {
    font-size: 2.5rem;
    font-weight: bold;
    color: #000;
    margin-bottom: 10px;
  }

  .heading p {
    font-size: 1rem;
    color: #666;
    line-height: 1.5;
  }


  .product-wrapper ul {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .product {
    flex: 1 1 30%;
    max-width: calc(33.333% - 20px);
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin: 10px 0;
  }

  .product:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }

  .product img {
    width: 100%;
    max-width: 250px;
    height: 300px;
    margin-bottom: 10px;
    border-radius: 8px;
  }

  .woocommerce-loop-product__title {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin: 10px 0;
  }

  .custom-product-rating {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 5px 0;
    gap: 3px;
    font-size: 16px;
  }

  .custom-product-rating .star {
    color: #ccc;
  }

  .custom-product-rating .star.filled {
    color: #FFD700;
  }

  .custom-product-rating .rating-count {
    font-size: 14px;
    color: #555;
    margin-left: 5px;
  }

  .price {
    font-size: 18px;
    font-weight: bold;
    color: #333;
  }

  .price del {
    font-size: 14px;
    color: #999;
    margin-right: 5px;
  }

  .pstatus-absoulte+.pstatus-absoulte {
    margin-top: 5px;
  }

  .add_to_cart_absolute a {
    position: absolute !important;
    top: -8px;
    right: -2px;
  }

  .add_to_cart_absolute a:hover {
    background-color: transparent !important;
  }

  .category-information {
    background-color: #fff;
    padding: 40px 20px;
  }

  .category-information .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
  }

  .category-information .info {
    flex: 1;
    text-align: left;
  }

  .category-information .info h3 {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #000;
    background-color: #000;
    color: #fff;
    padding: 10px 20px;
    display: inline-block;
    border-radius: 5px;
  }

  .category-information .info .description {
    font-size: 1rem;
    line-height: 1.5;
    color: #666;
  }

  .category-information .image {
    flex: 1;
    display: flex;
    justify-content: flex-end;
  }

  .category-information .image img {
    max-width: 100%;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .product-filters-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 20px;
    padding: 10px 0;
    border-bottom: 1px solid #ddd;
  }

  .product-filters-item-left {
    display: flex;
    align-items: center;
    gap: 20px;
  }

  .product-filters .filter-group {
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .product-filters label {
    font-weight: bold;
    font-size: 14px;
    margin-right: 5px;
  }

  .product-filters select {
    padding: 5px 10px;
    font-size: 14px;
    border: none;
    background-color: white;
    cursor: pointer;
  }

  .container-cat {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: white;
    padding: 50px 20px;
  }

  .container-cat .hero-content h1 {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .container-cat .hero-content p {
    margin: 10px 0;
    font-size: 1.2rem;
    color: #fff;
  }

  .container-cat .hero-content a {
    display: inline-block;
    background-color: white;
    color: black;
    padding: 10px 20px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 10px;
  }

  .container-cat .hero-content a:hover {
    background-color: #f0f0f0;
  }

  .container-cat .shop-hero {
    display: flex;
    gap: 30px;
    margin-top: 30px;
  }

  .container-cat .hero-item {
    flex: 1;
    position: relative;
  }

  .container-cat .hero-item img {
    width: 326px;
    height: 426px;
    border-radius: 5px;
    filter: brightness(0.8);
    transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
  }

  .container-cat .hero-item img:hover {
    transform: translate(-10px, -10px);
    filter: brightness(1);
    box-shadow: 10px 10px 20px rgba(255, 255, 255, 0.5);
  }

  .browse_our_categories {
    padding: 50px 20px;
  }

  .browse_our_categories .heading h5 {
    font-size: 1rem;
    color: #8dc63f;
    margin-bottom: 10px;
    text-transform: uppercase;
  }

  .browse_our_categories .heading h2 {
    font-size: 2.5rem;
    font-weight: bold;
    color: #000;
    margin-bottom: 15px;
  }

  .browse_our_categories .heading p {
    font-size: 1rem;
    color: #666;
    margin-bottom: 40px;
  }


  .swiper-slide {
    background-color: #F8F8F8;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: left;
  }

  .swiper-slide img {
    width: 75%;
    height: auto;
    margin-bottom: 20px;
    border-radius: 5px;
  }

  .swiper-slide h2 {
    font-size: 1.2rem;
    font-weight: bold;
    color: #000;
    margin-bottom: 10px;
  }

  .swiper-slide .product-cat-infor {
    display: flex;
    justify-content: space-between;
  }

  .swiper-slide p {
    font-size: 0.9rem !important;
    color: #666;
    margin-bottom: 15px;
    width: 60%;
  }

  .swiper-slide a {
    height: max-content;
    display: inline-block;
    background-color: #000;
    color: #fff;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9rem;
    font-weight: bold;
    transition: background-color 0.3s ease;
  }

  .swiper-slide a:hover {
    background-color: #444;
  }

  .inner {
    display: flex;
    justify-content: space-between;
    padding: 40px 20px;
    gap: 20px;
  }

  .left-arrival {
    flex: 1;
    background-color: #000;
    color: #fff;
    padding: 40px;
    border-radius: 10px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .left-arrival h2 {
    font-size: 2.5rem;
    font-weight: bold;
    line-height: 1.5;
    margin-bottom: 20px;
  }

  .left-arrival h2 span {
    color: #8dc63f;
  }

  .left-arrival a {
    display: inline-block;
    background-color: #fff;
    color: #000;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    text-decoration: none;
    margin-bottom: 15px;
  }

  .left-arrival a:hover {
    background-color: #8dc63f;
    color: #fff;
  }

  .left-arrival img {
    max-width: 100%;
    margin-top: 20px;
  }

  .right-arrival {
    flex: 2;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  .two-item-bestseller {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    padding: 40px 20px;
    background-color: #f9f9f9;
  }

  .bestseller-item {
    flex: 1;
    background-color: #fff;
    border: 1px solid #eaeaea;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: flex-start;
    text-align: left;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .bestseller-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }

  .bestseller-item img {
    max-width: 100px;
    height: auto;
    margin-bottom: 15px;
  }

  .bestseller-item h4 {
    font-size: 1rem;
    color: #8dc63f;
    font-weight: bold;
    margin-bottom: 10px;
    text-transform: uppercase;
  }

  .bestseller-item h3 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #000;
    margin-bottom: 10px;
  }

  .bestseller-item p {
    font-size: 1rem;
    color: #666;
    margin-bottom: 20px;
    line-height: 1.5;
  }

  .bestseller-item a {
    display: inline-block;
    background-color: #000;
    color: #fff;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
  }

  .bestseller-item a:hover {
    background-color: #444;
  }

  .grid-best-seller ul {
    justify-content: space-between;
  }

  .shop-partner .swiper-slide {
    width: 183px !important;
  }

  #breadcrumbs .breadcrumb-separator {
    color: #aaa !important;
    font-size: 1.2em;
    margin: 0 5px;
    vertical-align: middle;
  }

  .link-page #breadcrumbs a {
    font-family: "ProximaNova", sans-serif, san-serif;
    color: #9F9F9F !important;
  }

  .link-page #breadcrumbs {
    font-size: 14px;
    line-height: 20px;
  }

  .woocommerce-shop .left-arrival h2+a+a+img.banner-mb {
    height: 900px;
    object-fit: cover;
  }

  .woocommerce-shop .left-arrival h2+a {
    z-index: 1;
    position: relative;
  }

  .woocommerce-shop .left-arrival h2 {
    z-index: 1;
    position: relative;
  }

  .woocommerce-shop .left-arrival h2+a+a {
    z-index: 1;
    position: relative;
  }

  .product-cat-title {
    height: unset !important;
    background-color: unset !important;
    padding: unset !important;
  }

  .tax-product_cat.woocommerce-page .product-filters select {
    padding: 12px 32px 12px 12px !important;
  }

  .tax-product_cat.woocommerce-page .product-filters #price {
    padding-right: 0px !important;
  }

  .tax-product_cat.woocommerce-page .product-filters #sort-by {
    padding-right: 21px !important;
  }

  @media (max-width: 1200px) {
    .woocommerce-shop .left-arrival h2+a+a+img.banner-mb {
      height: 100% !important;
    }
  }

  .product-cat-infor {
    align-items: center;
  }

  .shoptype_content h2 {
    margin-top: 20px;
  }

  .container_v2 {
    max-width: 772px;
    margin: 0 auto;
  }

  .resource-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
  }

  .resource-header svg {
    cursor: pointer;
    transition: transform 0.3s ease;
  }

  .resource-header svg.rotated {
    transform: rotate(180deg);
  }

  .resource-content-references {
    display: none;
  }

  .about-the-author {
    margin-bottom: 20px;
  }

  .profile-card {
    display: flex;
  }

  .profile-image {
    flex-shrink: 0;
    margin-right: 16px;
  }

  .profile-image img {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
  }

  .profile-info {
    flex: 1;
  }

  .profile-info h2 {
    font-size: 18px;
    margin: 0 0 4px;
  }

  .profile-info .role {
    font-size: 14px;
    margin: 0 0 8px;
  }

  .about-content .description {
    font-size: 14px;
    line-height: 1.6;
  }

  .about-content .see-more {
    color: #4caf50;
    text-decoration: none;
  }

  .about-content .see-more:hover {
    text-decoration: underline;
  }

  .short-text {
    display: inline;
  }

  .full-text {
    display: none;
  }

  .browse_our_categories .swiper-slide img {
    height: 300px;
    object-fit: contain;
  }

  .shop-banner-mb {
    display: none;
  }

  .shop-banner-pc {
    display: block;
  }

  @media (max-width: 430px) {
    .shop-banner-mb {
      display: block;
      margin-bottom: 20px;
      margin-top: -15px;
    }

    .shop-banner-pc {
      display: none;
    }
  }

  .bestseller-item .link_best_sale {
    background-color: transparent !important;
    ;
  }

  .bestseller-item .shop-link {
    padding: 13px 62px;
    border-radius: 0;
    font-weight: 500;
    display: inline-block;
    background-color: #000;
    color: #fff;
    text-decoration: none;
    transition: background-color 0.3s ease;
    margin-top: 5px;
  }
</style>
<div class="link-page shop-link-page">
  <div class="container">
    <?php
    if (is_product_category()) {
      $current_category = get_queried_object();

      echo '<nav aria-label="breadcrumb" id="breadcrumbs">';
      echo '<a href="' . esc_url(home_url()) . '">' . 'Home' . '</a>';
      echo '<span class="breadcrumb-separator"> | </span>';
      echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">' . 'Shop' . '</a>';
      if ($current_category) {
        echo '<span class="breadcrumb-separator"> | </span>';
        echo '<strong>' . esc_html($current_category->name) . '</strong>';
      }
      echo '</nav>';
    }
    ?>


  </div>
</div>

<?php
$shop_hero = get_field('shop_hero', wc_get_page_id('shop'));
if (!empty($shop_hero)) {
?>
  <?php
  if (isset($term->term_id)) {
    $term = get_term($term->term_id);
    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
    $image_url = wp_get_attachment_url($thumbnail_id);
    $shoptype_content = get_term_meta($term->term_id, 'shoptype_content', true);
    $resource =  get_field('resource', 'term_' . $term->term_id);
    $references =  get_field('references', 'term_' . $term->term_id);
    $disclaimer =  get_field('disclaimer', 'term_' . $term->term_id);
    $faqs =  get_field('shoptype_question', 'term_' . $term->term_id);
  ?>
    <?php
    ?>
    <div class="category-information">
      <div class="container">
        <div class="info">
          <h3><?php echo $term->name; ?></h3>
          <div class="description">
            <?php echo esc_html($term->description); ?>
          </div>
        </div>
        <div class="image">
          <?php
          $background = get_field('background', 'term_' . $term->term_id);
          ?>
          <img src="<?php echo $background['url']; ?>" alt="">
        </div>
      </div>
    </div>

    <div class="product-list">
      <div class="container">
        <div class="product-filters">
          <form id="product-filter-form" method="GET">
            <div class="product-filters-item">
              <div class="product-filters-item-left">
                <div class="filter-group">
                  <label for="availability">Filter:</label>
                  <select name="availability" id="availability" onchange="this.form.submit();">
                    <option value="">Availability</option>
                    <option value="instock" <?php echo isset($_GET['availability']) && $_GET['availability'] == 'instock' ? 'selected' : ''; ?>>In Stock</option>
                    <option value="outofstock" <?php echo isset($_GET['availability']) && $_GET['availability'] == 'outofstock' ? 'selected' : ''; ?>>Out of Stock</option>
                  </select>
                </div>
                <div class="filter-group">
                  <select name="price" id="price" onchange="this.form.submit();">
                    <option value="">Price</option>
                    <option value="low-to-high" <?php echo isset($_GET['price']) && $_GET['price'] == 'low-to-high' ? 'selected' : ''; ?>>Low to High</option>
                    <option value="high-to-low" <?php echo isset($_GET['price']) && $_GET['price'] == 'high-to-low' ? 'selected' : ''; ?>>High to Low</option>
                  </select>
                </div>
              </div>
              <div class="filter-group sort-by">
                <label for="sort-by">Sort by:</label>
                <select name="sort_by" id="sort-by" onchange="this.form.submit();">
                  <option value="">Choose</option>
                  <option value="best-selling" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'best-selling' ? 'selected' : ''; ?>>Best Selling</option>
                </select>
              </div>
            </div>
          </form>
        </div>

        <div class="content-wrapper">
          <div class="product-wrapper">
            <?php
            $query_args = array(
              'post_type'      => 'product',
              'post_status'    => 'publish',
              'posts_per_page' => 6,
              'orderby'        => 'date',
              'order'          => 'DESC',
              'tax_query'      => array(
                array(
                  'taxonomy' => 'product_cat',
                  'field'    => 'term_id',
                  'terms'    => $term->term_id,
                  'include_children' => false,
                ),
              ),
            );

            if (!empty($_GET['availability'])) {
              if ($_GET['availability'] == 'instock') {
                $query_args['meta_query'][] = array(
                  'key'     => '_stock_status',
                  'value'   => 'instock',
                  'compare' => '=',
                );
              } elseif ($_GET['availability'] == 'outofstock') {
                $query_args['meta_query'][] = array(
                  'key'     => '_stock_status',
                  'value'   => 'outofstock',
                  'compare' => '=',
                );
              }
            }

            if (!empty($_GET['price'])) {
              if ($_GET['price'] == 'low-to-high') {
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = '_price';
                $query_args['order'] = 'ASC';
              } elseif ($_GET['price'] == 'high-to-low') {
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = '_price';
                $query_args['order'] = 'DESC';
              }
            }

            if (!empty($_GET['sort_by']) && $_GET['sort_by'] == 'best-selling') {
              $query_args['orderby'] = 'meta_value_num';
              $query_args['meta_key'] = 'total_sales';
              $query_args['order'] = 'DESC';
            }

            $products_query = new WP_Query($query_args);

            echo "<ul>";
            if ($products_query->have_posts()) {
              while ($products_query->have_posts()) {
                $products_query->the_post();
                wc_get_template_part('content', 'product-related');
              }
              wp_reset_postdata();
            } else {
              echo '<p>No products found.</p>';
            }
            echo "</ul>";
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php
    $app = get_field('intro_app', 'option');
    if (!empty($app[0])):
      $app = $app[0];
      if (!empty($app['title'])) {
        if (strpos($app['title'], 'Enfit') !== false) {
          $app['title'] = str_replace('Enfit', '<strong>Enfit</strong>', $app['title']);
        }
      }

      if (!empty($app['discount'])) {
        $app['discount'] = preg_replace('/(\d+%)/', '<strong>$1</strong>', $app['discount']);
      }
      $storeLink = get_field('footer_store', 'option');
      $explore = !empty($app['explore']) ? $app['explore'] : '';
      $store = $storeLink ?: '';
    ?>

      <section class="app-section app-section-new mb">
        <div class="container">
          <div class="content app-content">
            <h3>Enfit App</h3>
            <?php if (!empty($app['title'])): ?>
              <p class="has-x-large-font-size"><?= get_field('enfit_title', 'option') ?></p>
            <?php endif; ?>
            <?php if (!empty($app['description'])): ?>
              <p><?= get_field('enfit_description', 'option') ?></p>
            <?php endif; ?>
            <div class="enfit-action mr-bottom-20 flex">
              <?php if ($explore): ?>
                <a href="<?= $explore ?>" id="">Explore Now</a>
              <?php endif; ?>
              <?php if ($store): ?>
                <a target="_blank" href="<?= $store ?>" class="home-store">
                  <img src="<?= get_template_directory_uri() . '/assets/images/enfit/store.svg' ?>" alt="">
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <div class="container_v2">
      <div class="shoptype_content">
        <div class="container">
          <?php echo $shoptype_content; ?>
        </div>
      </div>
      <?php $alternative_choices_status =  get_field('alternative_choices_category_status', 'term_' . $term->term_id);
      $alternative_choices =  get_field('item_alternative_choices_category', 'term_' . $term->term_id);
      ?>
      <?php if ($alternative_choices_status) { ?>
        <div class="alternative-choices">
          <div class="container">
            <h3>Alternative Choices</h3>
            <div class="choices-container">
              <?php
              if (!empty($alternative_choices)) {
                foreach ($alternative_choices as $item) {

              ?>
                  <div class="choice-card">
                    <a href="<?php echo $item['url'] ? ($item['url']) : '#'; ?>">
                      <div class="choice-image">
                        <img src="<?php echo $item['image'] ?? ""; ?>" alt="<?php echo $item['title'] ?? ""; ?>" />
                      </div>
                    </a>
                    <a href="<?php echo $item['url'] ? ($item['url']) : '#'; ?>">
                      <h3><?php echo $item['title'] ?? ""; ?></h3>
                    </a>
                    <div class="choice-details">
                      <div class="pros">
                        <h4>Pros</h4>
                        <ul>
                          <?php echo $item['pros'] ?? ""; ?>
                        </ul>
                      </div>
                      <div class="cons">
                        <h4>Cons</h4>
                        <ul>
                          <?php echo $item['cons'] ?? ''; ?>
                        </ul>
                      </div>
                    </div>
                    <button class="see-details" onclick="window.location.href='<?php echo $item['url'] ? ($item['url']) : '#'; ?>'">
                      See Details
                    </button>
                  </div>
              <?php
                }
              }
              ?>
            </div>
          </div>
        </div>
      <?php } ?>
      <div class="faqs">
        <div class="container">
          <h3>Frequently Asked Questions</h3>
          <?php
          foreach ($faqs as $faq) {
          ?>
            <div>
              <div class="question">
                <span><?php echo $faq['question']; ?></span>
              </div>
              <div class="answer">
                <span><?php echo $faq['answer']; ?></span>
              </div>
            </div>

          <?php } ?>
        </div>
      </div>

      <?php if ($disclaimer) { ?>
        <div class="resource">
          <div class="container">
            <div class="resource-wrapper">
              <div class="resource-header">
                <h3>Resources</h3>
                <svg width="18" height="10" viewBox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15.75 1.4668L9 8.2168L2.25 1.4668" stroke="black" stroke-width="2" stroke-linecap="square" stroke-linejoin="round" />
                </svg>
              </div>
              <div class="resource-content">
                <div class="resource-content-disclaimer">
                  <?php echo $disclaimer; ?>
                </div>
                <hr>
                <div class="resource-content-references">
                  <?php echo $references; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
      <?php
      $comment = get_field('comment', 'term_' . $term->term_id);
      if ($comment): ?>
        <div class="about-the-author">
          <div class="container">
            <div class="about-wrapper">
              <h3>About the Author</h3>
              <div class="about-content">
                <?php foreach ($comment as $item): ?>
                  <div>
                    <div class="profile-card">
                      <div class="profile-image">
                        <?php if (!empty($item['avatar'])): ?>
                          <img src="<?= esc_url($item['avatar']); ?>" alt="<?= esc_attr($item['name']); ?>'s Avatar">
                        <?php endif; ?>
                      </div>
                      <div class="profile-info">
                        <h2><?= esc_html($item['name']); ?></h2>
                        <p class="role">Writer</p>
                      </div>
                    </div>
                  </div>
                  <div class="description">
                    <p class="short-text">
                      <?= !empty($item['note']) ? mb_strimwidth(strip_tags($item['note'], '<strong><em><a>'), 0, 200, '...') : 'No content available'; ?>
                    </p>
                    <span class="full-text" style="display: none;">
                      <?= !empty($item['note']) ? wp_kses_post($item['note']) : 'No content available'; ?>
                    </span>
                    <a href="#" class="see-more">See More</a>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>

    <div class="grid-best-seller">
      <div class="container">
        <div class="heading">
          <?php echo $best_seller_top; ?>
        </div>
        <?php
        $products_query = new WP_Query(array(
          'post_type'      => 'product',
          'post_status'    => '_wc_product_sales',
          'posts_per_page' => 3,
          'meta_key'       => 'total_sales',
          'orderby'        => 'meta_value_num',
          'order'          => 'DESC',
        ));

        echo "<ul>";
        if ($products_query->have_posts()) {
          while ($products_query->have_posts()) {
            $products_query->the_post();
            wc_get_template_part('content', 'product-related');
          }
          wp_reset_postdata();
        } else {
          echo '<p>No products found.</p>';
        }
        echo "</ul>";
        ?>
      </div>
    </div>
  <?php
  } else {
  ?>
    <div class="hero " style="background-image: url(<?php echo $top_banner_image['url']; ?>); background-color: #E0E2F1; background-size: cover; background-repeat: no-repeat; background-position: center center;">
      <div class="container container-cat">
        <div class="hero-content">
          <?php echo $top_banner; ?>
        </div>
        <div class="shop-hero">
          <?php
          foreach ($shop_hero as $key => $value) {
          ?>
            <div class="hero-item">
              <img src="<?php echo $value['image']['url']; ?>" alt="">
            </div>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="browse_our_categories">
      <div class="container">
        <div class="heading">
          <?php echo $browse_our_categories; ?>
        </div>
      </div>
      <div class="swiper">
        <div class="swiper-wrapper">
          <?php
          $categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => 0,
          ));

          if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $category) {
              if ($category->slug === 'uncategorized') {
                continue;
              }

              echo '<div class="swiper-slide">';
              $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);

              if ($thumbnail_id) {
                $thumbnail_url = wp_get_attachment_url($thumbnail_id);
                echo '<img src="' . esc_url($thumbnail_url) . '" alt="' . esc_attr($category->name) . '">';
              }
              echo '<div class="product-cat-infor">';
              echo '<div>';
              echo '<a class="product-cat-title" href="' . esc_url(get_term_link($category)) . '"><h2>' . esc_html($category->name) . '</h2></a>';
              echo '<p>' . esc_html($short_description) . '</p>';
              echo '</div>';
              echo '<a class="product-cat-link" href="' . esc_url(get_term_link($category)) . '">Shop now</a>';
              echo '</div>';
              echo '</div>';
            }
          }
          ?>
        </div>
      </div>
    </div>

    <div class="shop-banner-mb">
      <div class="shop-banner" style="background-image: url(<?php echo $banner_image['url']; ?>);">
        <div class="container">
          <?php echo $banner_content; ?>
        </div>
      </div>
      <?php
      $shop_partner = get_field('shop_partner', wc_get_page_id('shop'));
      if (!empty($shop_partner)) {
      ?>

        <div class="box-list shop-partner">
          <div class="container">
            <div class="swiper swiper-brand">
              <div class="swiper-wrapper">
                <?php
                foreach ($shop_partner as $key => $value) {
                ?>
                  <div class="swiper-slide">
                    <div class="swiper-slide-img">
                      <img loading="lazy" src="<?php echo $value['url']; ?>" alt="Time">
                    </div>
                  </div>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    <div class="product-list ">
      <div class="container">

        <div class="heading">
          <?php echo $new_arrivals; ?>
        </div>
        <style>
          .inner .product {
            flex: 1 1 calc(50% - 20px);
            max-width: calc(52.333% - 20px);
          }
        </style>
        <div class="inner">
          <div class="left-arrival">
            <div class="content">
              <h2>Reach<br />
                your goals<br />
                with <span>Enfit</span></h2>
              <a href="#">Explore Now</a>
              <a href="#"><img class="banner-mb" src="<?php echo get_template_directory_uri(); ?>/assets/img/Badges.png" alt=""></a>
              <img class="banner-mb" src="<?php echo $new_arrival_img['url']; ?>" alt="">
            </div>
          </div>
          <div class="right-arrival">

            <div class="content-wrapper">
              <div class="product-wrapper">
                <?php
                $products_query = new WP_Query(array(
                  'post_type' => 'product',
                  'post_status' => 'publish',
                  'posts_per_page' => 4
                ));

                echo "<ul>";
                if ($products_query->have_posts()) {
                  while ($products_query->have_posts()) {
                    $products_query->the_post();
                    wc_get_template_part('content', 'product-related');
                  }
                  wp_reset_postdata();
                } else {
                  echo '<p>No products found.</p>';
                }
                echo "</ul>";
                ?>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="shop-banner-pc">
      <div class="shop-banner" style="background-image: url(<?php echo $banner_image['url']; ?>);">
        <div class="container">
          <?php echo $banner_content; ?>
        </div>
      </div>
      <?php
      $shop_partner = get_field('shop_partner', wc_get_page_id('shop'));
      if (!empty($shop_partner)) {
      ?>

        <div class="box-list shop-partner">
          <div class="container">
            <div class="swiper swiper-brand">
              <div class="swiper-wrapper">
                <?php
                foreach ($shop_partner as $key => $value) {
                ?>
                  <div class="swiper-slide">
                    <div class="swiper-slide-img">
                      <img loading="lazy" src="<?php echo $value['url']; ?>" alt="Time">
                    </div>
                  </div>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>

    <div class="grid-best-seller">
      <div class="container">
        <div class="heading">
          <?php echo $best_seller_top; ?>
        </div>
        <div class="two-item-bestseller">
          <a class="link_best_sale" href="<?= get_field('best_seller_left_url', wc_get_page_id('shop')); ?>">
            <div class="bestseller-item item-left">
              <div>
                <?= htmlspecialchars_decode($best_seller_left); ?>
                <span class="shop-link">
                  Shop now
                </span>
              </div>
              <img src="<?php echo $best_seller_left_image['url']; ?>" alt="">
            </div>
          </a>
          <a class="link_best_sale" href="<?= get_field('best_seller_right_url', wc_get_page_id('shop')); ?>">
            <div class="bestseller-item item-right">
              <div>
                <?= htmlspecialchars_decode($best_seller_right); ?>
                <span class="shop-link">
                  Shop now
                </span>
              </div>
              <img src="<?php echo $best_seller_right_image['url']; ?>" alt="">
            </div>
          </a>
        </div>
        <script>
          // document.addEventListener("DOMContentLoaded", () => {
          //   const anchors = document.querySelectorAll(".two-item-bestseller a");

          //   anchors.forEach(anchor => {
          //     if (!anchor.textContent.trim() && !anchor.querySelector("img")) {
          //       anchor.outerHTML = anchor.innerHTML;
          //     }
          //   });
          // });
        </script>
        <style>
          .grid-best-seller .product {
            flex: 1 1 calc(50% - 20px);
            max-width: calc(33.633% - 20px);
          }
        </style>
        <?php
        $products_query = new WP_Query(array(
          'post_type'      => 'product',
          'post_status'    => '_wc_product_sales',
          'posts_per_page' => 6,
          'meta_key'       => 'total_sales',
          'orderby'        => 'meta_value_num',
          'order'          => 'DESC',
        ));
        echo "<ul>";
        if ($products_query->have_posts()) {
          while ($products_query->have_posts()) {
            $products_query->the_post();
            wc_get_template_part('content', 'product-related');
          }
          wp_reset_postdata();
        } else {
          echo '<p>No products found.</p>';
        }
        echo "</ul>";
        ?>
      </div>
    </div>

  <?php } ?>

<?php
}
?>



<?php

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

?>


<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/woocommerce/css/product.css' ?>?version=0.0.5">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/woocommerce/css/product-archive.css' ?>?version=0.0.37">

<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/woocommerce/css/swiper-bundle.min.css' ?>?version=0.0.1">
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/woocommerce/js/swiper-bundle.min.js' ?>?version=0.0.1"></script>

<script type="text/javascript">
  var swiperBrand = new Swiper('.shop-partner .swiper-brand', {
    loop: true,
    speed: 10000,
    spaceBetween: 0,
    autoplay: {
      delay: 0,
      disableOnInteraction: false,
    },
    allowTouchMove: false,
    slidesPerView: 6,
    breakpoints: {
      320: {
        slidesPerView: 2,
      },
      576: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 3,
      },
      992: {
        slidesPerView: 6,
      },
      1280: {
        slidesPerView: 6,
      },
    }
  });
  if (jQuery(window).width() >= 991) {
    jQuery('.product-list.best-seller').each(function() {
      var _this = jQuery(this);
      var window_width = jQuery(window).width();
      var container_width = _this.find('.container').width();
      var content_width = _this.find('.content-wrapper').width();
      var more = (window_width - container_width) / 2;
      _this.find('.content-wrapper').css('width', content_width + more);
    });
  }

  var browseOurCategories = new Swiper('.browse_our_categories .swiper', {
    loop: true,
    grabCursor: true,
    speed: 500,
    spaceBetween: 30,
    autoplay: false,
    effect: 'slide',
    centeredSlides: true,
    slidesPerView: 3,
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      767: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
    }
  });
  var swiperBestSeller = new Swiper('.swiper-best-seller', {
    loop: false,
    grabCursor: true,
    speed: 500,
    spaceBetween: 10,
    parallax: true,
    autoplay: false,
    effect: 'slide',
    centeredSlides: false,
    slidesPerView: 2,
    pagination: {
      el: ".swiper-pagination",
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
      },
      767: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
    }
  });

  jQuery(document).ready(function($) {
    $(".answer").hide();

    $(".question").on("click", function() {
      $(".answer").not($(this).next()).slideUp();

      $(this).next(".answer").stop(true, true).slideToggle();
    });
  });

  jQuery(document).ready(function($) {
    $(".see-more").on("click", function(e) {
      e.preventDefault();

      const description = $(this).closest(".description");

      const shortText = description.find(".short-text");
      const fullText = description.find(".full-text");
      $(this).hide();
      if (shortText.length && fullText.length) {
        if (fullText.is(":hidden")) {
          fullText.show();
          shortText.hide();
        } else {
          fullText.hide();
          shortText.show();
        }
      }
    });
  });

  jQuery(document).ready(function($) {
    $(".resource-header svg").on("click", function() {
      const $resourceContent = $(this).closest(".resource-wrapper").find(".resource-content-references");
      $resourceContent.slideToggle(300);
      $(this).toggleClass("rotated");
    });
  });
</script>

<?php

get_footer('shop');
