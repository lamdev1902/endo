<?php
/* Template Name: My Profile */
get_header();
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
// Check if the form is submitted
if (isset($_POST['update_billing_address']) && isset($_POST['billing_address_nonce'])) {
    // Verify the nonce for security
    if (!wp_verify_nonce($_POST['billing_address_nonce'], 'update_billing_address')) {
        return;
    }

    // Get the current user
    $current_user = wp_get_current_user();

    // Prepare the data to update
    $billing_fields = [
        'billing_full_name' => sanitize_text_field($_POST['billing_full_name']),
        'billing_first_name' => sanitize_text_field($_POST['billing_first_name']),
        'billing_last_name'  => sanitize_text_field($_POST['billing_last_name']),
        'billing_company'    => sanitize_text_field($_POST['billing_company']),
        'billing_address_1'  => sanitize_text_field($_POST['billing_address_1']),
        'billing_address_2'  => sanitize_text_field($_POST['billing_address_2']),
        'billing_city'       => sanitize_text_field($_POST['billing_city']),
        'billing_postcode'   => sanitize_text_field($_POST['billing_postcode']),
        'billing_country'    => sanitize_text_field($_POST['billing_country']),
        'billing_state'      => sanitize_text_field($_POST['billing_state']),
        'billing_phone'      => sanitize_text_field($_POST['billing_phone']),
        'billing_email'      => sanitize_email($_POST['billing_email']),
    ];

    // Update each field in the user meta
    foreach ($billing_fields as $field_key => $field_value) {
        update_user_meta($current_user->ID, $field_key, $field_value);
    }
}


?>
<div id="content" class="site-content">
    <div class="container">
        <div class="link-page">
            <p id="breadcrumbs"><span><span><a href="<?php echo home_url(); ?>">Home</a></span> » <span class="breadcrumb_last" aria-current="page"><?php the_title(); ?></span></span></p>
        </div>
        <article class="page-custom">
            <div class="heading">
                <h1 class="text-center">
                    <?php the_title(); ?>
                </h1>
                <a href="<?php echo wc_get_cart_url(); ?>">Continue to shopping</a>
            </div>

            <?php
            // Get the current user
            $current_user = wp_get_current_user();

            // Get current billing address values
            $billing_address = [
                'billing_full_name' => get_user_meta($current_user->ID, 'billing_full_name', true),
                'billing_first_name' => get_user_meta($current_user->ID, 'billing_first_name', true),
                'billing_last_name' => get_user_meta($current_user->ID, 'billing_last_name', true),
                'billing_company' => get_user_meta($current_user->ID, 'billing_company', true),
                'billing_address_1' => get_user_meta($current_user->ID, 'billing_address_1', true),
                'billing_address_2' => get_user_meta($current_user->ID, 'billing_address_2', true),
                'billing_city' => get_user_meta($current_user->ID, 'billing_city', true),
                'billing_postcode' => get_user_meta($current_user->ID, 'billing_postcode', true),
                'billing_country' => get_user_meta($current_user->ID, 'billing_country', true),
                'billing_state' => get_user_meta($current_user->ID, 'billing_state', true),
                'billing_phone' => get_user_meta($current_user->ID, 'billing_phone', true),
                'billing_email' => get_user_meta($current_user->ID, 'billing_email', true)
            ];

            // Output the form HTML
            ob_start(); ?>

            <form id="custom-billing-address-form" class="custom-billing-address-form" method="post">
                <div class="inline inline-col-2">
                    <p>
                        <label for="billing_full_name">Name <span class="required">*</span></label>
                        <input type="text" name="billing_full_name" id="billing_full_name" value="<?php echo esc_attr($billing_address['billing_full_name']); ?>" required>
                    </p>
                    <p>
                        <label for="billing_email">Email <span class="required">*</span></label>
                        <input type="email" name="billing_email" id="billing_email" value="<?php echo esc_attr($billing_address['billing_email']); ?>" required>
                    </p>
                </div>
                <div class="group">
                    <h3>Address</h3>
                    <p>
                        <select name="billing_country" id="billing_country" required>
                            <?php
                            // Get WooCommerce countries list
                            $countries = WC()->countries->get_countries();
                            $selected_country = esc_attr($billing_address['billing_country']);

                            foreach ($countries as $country_code => $country_name) {
                                echo '<option value="' . esc_attr($country_code) . '" ' . selected($selected_country, $country_code, false) . '>' . esc_html($country_name) . '</option>';
                            }
                            ?>
                        </select>
                    </p>
                    <div class="inline inline-col-2">
                        <p>
                            <input type="text" name="billing_first_name" id="billing_first_name" placeholder="First name" value="<?php echo esc_attr($billing_address['billing_first_name']); ?>">
                        </p>
                        <p>
                            <input type="text" name="billing_last_name" id="billing_last_name" placeholder="Last name" value="<?php echo esc_attr($billing_address['billing_last_name']); ?>">
                        </p>
                    </div>
                    <p>
                        <input type="text" name="billing_company" id="billing_company" placeholder="Company" value="<?php echo esc_attr($billing_address['billing_company']); ?>">
                    </p>
                    <p>
                        <input type="text" name="billing_address_1" id="billing_address_1" placeholder="Address" value="<?php echo esc_attr($billing_address['billing_address_1']); ?>">
                    </p>
                    <p>
                        <input type="text" name="billing_address_2" id="billing_address_2" placeholder="Apartment, suite, etc. (optional)" value="<?php echo esc_attr($billing_address['billing_address_2']); ?>">
                    </p>
                    <p>
                        <input type="text" name="billing_phone" id="billing_phone" placeholder="Phone" value="<?php echo esc_attr($billing_address['billing_phone']); ?>">
                    </p>
                    <div class="inline inline-col-3">
                        <p>
                            <select name="billing_state" id="billing_state">
                                <?php
                                // Get the selected country and states for that country
                                $selected_country = esc_attr($billing_address['billing_country']);
                                $selected_state = esc_attr($billing_address['billing_state']);
                                $states = WC()->countries->get_states($selected_country);

                                if (!empty($states)) {
                                    foreach ($states as $state_code => $state_name) {
                                        echo '<option value="' . esc_attr($state_code) . '" ' . selected($selected_state, $state_code, false) . '>' . esc_html($state_name) . '</option>';
                                    }
                                } else {
                                    // If no states are available, display an empty option
                                    echo '<option value="">' . __('Select a state', 'woocommerce') . '</option>';
                                }
                                ?>
                            </select>
                        </p>
                        <p>
                            <input type="text" name="billing_city" id="billing_city" placeholder="City" value="<?php echo esc_attr($billing_address['billing_city']); ?>">
                        </p>
                        <p>
                            <input type="text" name="billing_postcode" id="billing_postcode" placeholder="Postcode" value="<?php echo esc_attr($billing_address['billing_postcode']); ?>">
                        </p>
                    </div>
                </div>
                <?php wp_nonce_field('update_billing_address', 'billing_address_nonce'); ?>
                <div class="inline inline-col-2">
                    <p><a href="<?php echo home_url(); ?>" class="button">Cancel</a></p>
                    <p><input type="submit" name="update_billing_address" value="Save"></p>
                </div>
            </form>
        </article>
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
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#billing_country').change(function() {
            var selectedCountry = $(this).val();
            var stateSelect = $('#billing_state');

            // Clear existing state options
            stateSelect.empty();
            // Fetch states via AJAX
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'get_states',
                    country: selectedCountry
                },
                success: function(response) {
                    var states = JSON.parse(response);

                    if ($.isEmptyObject(states)) {
                        stateSelect.append('<option value="">' + '<?php _e('Select a state', 'woocommerce'); ?>' + '</option>');
                    } else {
                        $.each(states, function(index, state) {
                            stateSelect.append('<option value="' + index + '">' + state + '</option>');
                        });
                    }
                }
            });
        });
    });
</script>