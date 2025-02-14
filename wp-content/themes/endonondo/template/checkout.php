<?php
/* Template Name: Checkout */
get_header();
$privacy_policy = get_field('privacy_policy', 'option');
$terms_of_service = get_field('terms_of_service', 'option');
$subscription_policy = get_field('subscription_policy', 'option');
?>
<style>
    #breadcrumbs .breadcrumb-separator {
        color: #aaa;
        font-size: 0.8em;
        margin: 0 5px;
        vertical-align: middle;
    }
    @media(max-width: 767px) {
        .wc-block-components-address-card__edit {
            width: 10% !important;
        }
    }

    @media(max-width: 430px) {
        .wc-block-components-address-card__edit {
            width: 15% !important;
        }
    }

    .wc-block-components-spinner {
        margin-left: -50%;
        margin-top: -15px;
    }
</style>
<div id="content" class="site-content home-page site-content-checkout">
    <div class="container">
        <div class="link-page">
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
        <article class="page-custom">
            <h1 class="text-center">
                <?php the_title(); ?>
            </h1>
            <?php the_content(); ?>
        </article>
        <div class="checkout-bottom-menu">
            <ul>
                <li><a href="<?php echo $privacy_policy; ?>">Privacy policy</a></li>
                <li><a href="<?php echo $terms_of_service; ?>">Terms of service</a></li>
                <li><a href="<?php echo $subscription_policy; ?>">Subscription policy</a></li>
            </ul>
        </div>
    </div>
</div><!-- .site-content -->
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
<?php get_footer(); ?>