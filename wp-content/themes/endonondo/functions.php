<?php
add_action('after_setup_theme', 'setup_woocommerce_support');

function setup_woocommerce_support()
{
    add_theme_support('woocommerce');
}

function enqueue_wc_variation_script()
{
    if (function_exists('is_product') && is_product()) {
        wp_enqueue_script('wc-add-to-cart-variation');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_wc_variation_script');



include(TEMPLATEPATH . '/shortcode/chart/chart-shortcode.php');
include(TEMPLATEPATH . '/shortcode/muscle/anatomy.php');
include(TEMPLATEPATH . '/shortcode/calorie/calorie-shortcode.php');
include(TEMPLATEPATH . '/shortcode/calorie/bmi-shortcode.php');
// include(TEMPLATEPATH.'/shortcode/calorie/chinese-gender-shortcode.php');
include(TEMPLATEPATH . '/shortcode/calorie/body-fat-shortcode.php');
include(TEMPLATEPATH . '/shortcode/calorie/ideal-weight-shortcode.php');
include(TEMPLATEPATH . '/shortcode/calorie/lean-body-mass-shortcode.php');
// include(TEMPLATEPATH.'/shortcode/calorie/healthy-weight-shortcode.php');
// include(TEMPLATEPATH.'/shortcode/calorie/age-shortcode.php');
// include(TEMPLATEPATH.'/shortcode/calorie/tdee-shortcode.php');
// include(TEMPLATEPATH.'/shortcode/calorie/army-body-fat-shortcode.php');
// include(TEMPLATEPATH.'/shortcode/calorie/absi-shortcode.php');
// include(TEMPLATEPATH.'/shortcode/calorie/adjusted-body-weight-shortcode.php');
// include(TEMPLATEPATH.'/shortcode/calorie/body-adiposity-index-shortcode.php');
include(TEMPLATEPATH . '/shortcode/calorie/bmr-shortcode.php');
include(TEMPLATEPATH . '/shortcode/calorie/repmax-shortcode.php');
// include(TEMPLATEPATH . '/woocommerce/woo-json.php');
include(TEMPLATEPATH . '/woocommerce/woo-functions.php');
function leea_filter_post_type_args($args, $post_type)
{
    if ('product' === $post_type) {
        $args['has_archive'] = false;
        $args['rewrite']['slug'] = 'shop';
    }
    return $args;
}

add_filter('register_post_type_args', 'leea_filter_post_type_args', 10, 2);
// Override Hook Breadcrum
add_filter('wpseo_breadcrumb_links', 'customize_yoast_breadcrumb_ex_links', 20);
function customize_yoast_breadcrumb_ex_links($links)
{
    global $template;

    if (basename($template) == 'single-tool_post.php') {
        $calculator_page = get_pages(array(
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template/calculator.php',
            'number' => 1
        ));

        if (!empty($calculator_page)) {
            $page = $calculator_page[0];

            if (isset($links[1])) {
                $clone = $links[1];
                $links[1]['url'] = get_permalink($page->ID);
                $links[1]['text'] = "Tool";
                $links[1]['id'] = $page->ID;

                $links[2] = $clone;
            }
        }
    } else if (basename($template) == 'single-exercise.php') {
        $calculator_page = get_pages(array(
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template/exercise.php',
            'number' => 1
        ));

        if (!empty($calculator_page)) {
            $page = $calculator_page[0];

            if (isset($links[1])) {
                $links[1]['url'] = get_permalink($page->ID);
                $links[1]['id'] = $page->ID;
            }
        }
    }



    return $links;
}
function enqueue_searchable_option_list_assets()
{
    wp_enqueue_script(
        'searchable-option-list-js',
        get_template_directory_uri() . '/assets/searchable/jquery.multiselect.js',
        array('jquery'),
        null,
        true
    );

    wp_enqueue_style(
        'searchable-option-list-css',
        get_template_directory_uri() . '/assets/searchable/jquery.multiselect.css',
        array(),
        null
    );
}

add_action('wp_enqueue_scripts', 'enqueue_searchable_option_list_assets');
function enqueue_exercise_search_script()
{
    if (is_page_template('template/exercise.php')) {
        wp_enqueue_script('ajax-search-script', get_template_directory_uri() . '/assets/js/ajax-search.js', array('jquery'), '1.0.3', true);

        wp_localize_script('ajax-search-script', 'exerciseSearch', array(
            'nonce' => wp_create_nonce('search_exercise_nonce'),
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_exercise_search_script');

function search_exercise()
{
    global $wpdb;

    $search_term = !empty($_POST['data']['name']) ? $_POST['data']['name'] : '';

    $additional_condition = '';

    if (!empty($_POST['data']['mt']) && is_array($_POST['data']['mt'])) {
        $mt_conditions = array_map(function ($muscleid) use ($wpdb) {
            return $wpdb->prepare("mt.id = %d", $muscleid);
        }, $_POST['data']['mt']);
        $additional_condition .= ' AND (' . implode(' OR ', $mt_conditions) . ')';
    }

    if (!empty($_POST['data']['eq']) && is_array($_POST['data']['eq'])) {
        $eq_conditions = array_map(function ($equipment_id) use ($wpdb) {
            return $wpdb->prepare("eq.id = %d", $equipment_id);
        }, $_POST['data']['eq']);
        $additional_condition .= ' AND (' . implode(' OR ', $eq_conditions) . ')';
    }

    $query = $wpdb->prepare(
        "
    SELECT DISTINCT e.id AS exercise_id, e.name AS exercise_name, e.image_male AS exercise_image, e.image_female AS exercise_image_female, e.slug AS exercise_slug
    FROM {$wpdb->prefix}exercise AS e
    LEFT JOIN {$wpdb->prefix}exercise_primary_option AS epo ON epo.exercise_id = e.id
    LEFT JOIN {$wpdb->prefix}exercise_muscle_anatomy AS ma ON ma.id = epo.muscle_id
    LEFT JOIN {$wpdb->prefix}exercise_muscle_type AS mt ON mt.id = ma.type_id
    LEFT JOIN {$wpdb->prefix}exercise_equipment_option AS eo ON eo.exercise_id = e.id
    LEFT JOIN {$wpdb->prefix}exercise_equipment AS eq ON eq.id = eo.equipment_id
    WHERE (e.name LIKE %s 
       OR ma.name LIKE %s 
       OR mt.name LIKE %s 
       OR eq.name LIKE %s)
       $additional_condition
       AND e.slug IS NOT NULL
        AND e.slug != ''
        AND e.active = 1
    ",
        '%' . $search_term . '%',
        '%' . $search_term . '%',
        '%' . $search_term . '%',
        '%' . $search_term . '%'
    );

    $exEquip = "
    SELECT eq.name
    FROM {$wpdb->prefix}exercise_equipment AS eq
    INNER JOIN {$wpdb->prefix}exercise_equipment_option AS eo 
        ON eq.id = eo.equipment_id
    WHERE eo.exercise_id = %d
";

    $exTag = "
    SELECT DISTINCT mt.id, mt.name
    FROM {$wpdb->prefix}exercise_muscle_type AS mt
    INNER JOIN {$wpdb->prefix}exercise_muscle_anatomy AS ma ON ma.type_id = mt.id
    INNER JOIN {$wpdb->prefix}exercise_primary_option AS epo ON epo.muscle_id = ma.id
    WHERE epo.exercise_id = %d
";

    $results = $wpdb->get_results($query, ARRAY_A);

    if (!empty($results)) {
        ob_start();

        foreach ($results as $ex):
            $exID = $ex['exercise_id'];

            $equipment = $wpdb->get_results($wpdb->prepare($exEquip, $exID), ARRAY_A);
            if (!empty($equipment)) {
                $equipment_names = array_column($equipment, 'name');

                $equipment = implode(', ', $equipment_names);
            } else {
                $equipment = '';
            }
            $mtExercises = $wpdb->get_results($wpdb->prepare($exTag, $exID), ARRAY_A);
            ?>
            <div class="mt flex">
                <div class="ex-img">
                    <a target="_blank" href="<?= home_url('/exercise/' . $ex['exercise_slug']); ?>">
                        <img src=" <?= $ex['exercise_image'] ?: $ex['exercise_image_female'] ?>" alt="">
                    </a>
                </div>
                <div class="ex-content">
                    <a target="_blank" href="<?= home_url('/exercise/' . $ex['exercise_slug']); ?>">
                        <p><strong><?= $ex['exercise_name'] ?></strong></p>
                    </a>

                    <p><strong>Equipment:</strong> <?= $equipment ?></p>
                    <div class="flex">
                        <?php foreach ($mtExercises as $mtex): ?>
                            <span class="has-ssmall-font-size"><?= esc_html($mtex['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php
        endforeach;
        $output = ob_get_clean();
        wp_send_json_success(
            $output
        );
    } else {
        wp_send_json_success();
    }
}
add_action('wp_ajax_search_exercise', 'search_exercise');
add_action('wp_ajax_nopriv_search_exercise', 'search_exercise');
function enqueue_infinite_scroll_script()
{
    if (is_category()) {
        global $wp_query;

        wp_enqueue_script(
            'infinite-scroll',
            get_template_directory_uri() . '/assets/js/infinite-scroll.js',
            array('jquery'),
            '1.0.0',
            true
        );
    } elseif (is_tag()) {
        global $wp_query;

        wp_enqueue_script(
            'infinite-scroll-tag',
            get_template_directory_uri() . '/assets/js/infinite-tag.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_infinite_scroll_script');

function load_more_posts()
{

    $paged = 6;
    $args = $_POST['query_vars'];
    $args['post_type'] = array('post', 'informational_posts', 'round_up', 'single_reviews', 'step_guide');
    $args['posts_per_page'] = 3;

    $paged = $_POST['page'] + 1;
    $args['paged'] = $paged;


    $the_query = new WP_Query($args);

    if ($the_query->have_posts()):
        while ($the_query->have_posts()):
            $the_query->the_post();
            $post_author_id = get_post_field('post_author', get_the_ID());
            $post_display_name = get_the_author_meta('display_name', $post_author_id);
            $post_author_url = get_author_posts_url($post_author_id);
            ?>
            <div class="news-it">
                <div class="news-box">
                    <div class="featured image-fit hover-scale">
                        <?php $image_featured = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php if ($image_featured): ?>
                                <img src="<?php echo $image_featured; ?>" alt="">
                            <?php else: ?>
                                <img src="<?php echo get_field('fimg_default', 'option'); ?>" alt="">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="info">
                        <?php $category = get_the_category(get_the_ID()); ?>
                        <?php if (!empty($category) && count($category) > 0): ?>
                            <div class="tag mr-bottom-16">
                                <?php
                                foreach ($category as $cat) { ?>
                                    <span><a href="<?php echo get_term_link($cat->term_id); ?>"><?php echo $cat->name; ?></a></span>
                                <?php } ?>
                            </div>
                        <?php endif; ?>
                        <p class="has-medium-font-size text-special clamp-2"><a class="pri-color-2"
                                href="<?php the_permalink(); ?>"><?php echo the_title(); ?></a></p>
                        <p class="has-small-font-size"><a target="_blank" class="sec-color-3"
                                href="<?php echo $post_author_url; ?>">By <?php echo $post_display_name; ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
    endif;
    die();
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

function custom_image_sizes_choose($sizes)
{
    unset($sizes['thumbnail']);
    unset($sizes['medium']);
    unset($sizes['large']);

    return array('full' => __('Full Size'));
}

add_filter('image_size_names_choose', 'custom_image_sizes_choose');

/* Replace Year current */
function year_shortcode()
{
    $year = date('Y');
    return $year;
}

add_filter('single_post_title', 'my_shortcode_title');
add_filter('the_title', 'my_shortcode_title');
add_filter('wp_title', 'my_shortcode_title');
function my_shortcode_title($title)
{
    $title = strip_tags($title);
    return do_shortcode($title);
}
add_filter('pre_get_document_title', function ($title) {
    // Make any changes here
    return do_shortcode($title);
}, 999, 1);

add_shortcode('Year', 'year_shortcode');
add_shortcode('year', 'year_shortcode');
/* year seo */
include(TEMPLATEPATH . '/sitemap/sitemap-loader.php');
include(TEMPLATEPATH . '/include/menus.php');
include(TEMPLATEPATH . '/hcfunction/update-modifile-be.php');
add_theme_support('post-thumbnails', array('post', 'page', 'product', 'informational_posts', 'round_up', 'single_reviews', 'step_guide'));
/* Script Admin */
function my_script()
{ ?>
    <style type="text/css">
        #dashboard_primary,
        #icl_dashboard_widget,
        #dashboard_right_now #wp-version-message,
        #wpfooter {
            display: none;
        }
    </style>
<?php }
add_action('admin_footer', 'my_script');
function custom_style_login()
{
    ?>
    <style type="text/css">
        .login h1 a {
            background-image: url("<?php echo get_template_directory_uri(); ?>/assets/images/endomondo-1.svg");
            background-size: 100% auto;
            height: 35px;
            width: 200px;
        }

        .wp-social-login-provider-list img {
            max-width: 100%;
        }
    </style>
<?php }
add_action('login_head', 'custom_style_login');
/* add css, jquery */
function theme_mcs_scripts()
{
    /* general css */
    wp_enqueue_style('style-slick', get_template_directory_uri() . '/assets/js/slick/slick.css');
    wp_enqueue_style('style-slick-theme', get_template_directory_uri() . '/assets/js/slick/slick-theme.css');
    wp_enqueue_style('style-swiper', get_template_directory_uri() . '/assets/js/swiper/swiper-bundle.min.css');
    wp_enqueue_style('style-main', get_template_directory_uri() . '/assets/css/main.css', '', '1.8.1');
    wp_enqueue_style('style-custom', get_template_directory_uri() . '/assets/css/custom.css', '', '1.9.5');
    wp_enqueue_style('style-base', get_template_directory_uri() . '/assets/css/base.css', '', '1.3.6');
    wp_enqueue_style('tool-css', get_template_directory_uri() . '/shortcode/calorie/assets/css/tool.css', '', '1.0.5');
    wp_enqueue_style('style-element', get_template_directory_uri() . '/assets/css/element.css', '', '2.1.5');
    wp_enqueue_style('style-responsive', get_template_directory_uri() . '/assets/css/responsive.css', '', '2.0.1');
    wp_enqueue_style('style-awesome', get_template_directory_uri() . '/assets/fonts/css/fontawesome.css');
    wp_enqueue_style('style-solid', get_template_directory_uri() . '/assets/fonts/css/solid.css');
    wp_enqueue_style('style-regular', get_template_directory_uri() . '/assets/fonts/css/regular.css');
    wp_enqueue_style('style-new', get_template_directory_uri() . '/assets/css/new.css', '', '6.8.0');
}
add_action('wp_enqueue_scripts', 'theme_mcs_scripts');

/* register page option ACF */
if (function_exists('acf_add_options_page')) {
    $parent = acf_add_options_page(array(
        'page_title' => 'Website Option',
        'menu_title' => 'Website Option',
        'icon_url' => 'dashicons-image-filter',
    ));
    acf_add_options_sub_page(array(
        'page_title' => 'Option',
        'menu_title' => 'Option',
        'parent_slug' => $parent['menu_slug'],
    ));
    acf_add_options_sub_page(array(
        'page_title' => 'Sitemap',
        'menu_title' => 'Sitemap',
        'parent_slug' => $parent['menu_slug'],
    ));
}
//add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    show_admin_bar(false);
}
/* Hide editor not use */
//add_action( 'admin_init', 'hide_editor_not_use' );
function hide_editor_not_use()
{
    if (isset($_GET['post']) && $_POST['post_ID']) {
        $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];
        if (!isset($post_id))
            return;

        $template_file = get_post_meta($post_id, '_wp_page_template', true);

        if ($template_file == 'template/home.php') {
            remove_post_type_support('page', 'editor');
        }
    }
}
/* Update date when publish post */
function post_unpublished($new_status, $old_status, $post)
{
    if ($old_status == 'future' && $new_status == 'publish') {
        $update_post = array(
            'ID' => $post->ID,
            'post_modified' => $post->post_date
        );
        wp_update_post($update_post);
    }
}
add_action('transition_post_status', 'post_unpublished', 10, 3);

add_filter('request', 'my_tag_nav');
function my_tag_nav($request)
{
    if (isset($request['post_tag'])) {
        $request['posts_per_page'] = 1;
    }
    return $request;
}

function custom_social_share_buttons_shortcode()
{
    ob_start(); ?>

    <div class="addtoany_share_buttons">
        <?php if (function_exists('ADDTOANY_SHARE_SAVE_KIT')) {
            ADDTOANY_SHARE_SAVE_KIT();
        } ?>
    </div>

    <?php
    return ob_get_clean();
}

function mytheme_comment($comment, $args, $depth)
{
    if ($comment->comment_approved == '0') {
        return false;
    }

    if ('div' === $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    } ?>
    <<?php echo $tag; ?>     <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>
        id="comment-<?php comment_ID() ?>"><?php
          if ('div' != $args['style']) { ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body"><?php
          } ?>
            <div class="flex section-header">
                <div class="comment-author vcard">
                    <?php
                    $comment_author_id = $comment->user_id;
                    if ($comment_author_id && user_can($comment_author_id, 'administrator') && $args['avatar_size'] != 0) {
                        echo get_avatar($comment, $args['avatar_size']);
                    }
                    printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()); ?>
                </div><?php
                if ($comment->comment_approved == '0') { ?>
                    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.'); ?></em><br /><?php
                } ?>
                <div class="comment-meta commentmetadata">
                    <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
                        <p class="has-ssmall-font-size"><?php
                        /* translators: 1: date, 2: time */
                        printf(
                            __('%1$s at %2$s'),
                            get_comment_date(),
                            get_comment_time()
                        ); ?></p>
                    </a><?php
                    edit_comment_link(__('(Edit)'), '  ', ''); ?>
                </div>
            </div>

            <div class="cmt-box">
                <?php comment_text(); ?>
            </div>
        </div>
        <?php if ($depth < 3): ?>
            <div class="reply"><?php
            comment_reply_link(
                array_merge(
                    $args,
                    array(
                        'add_below' => $add_below,
                        'depth' => $depth,
                        'max_depth' => $args['max_depth']
                    )
                )
            ); ?>
            </div>
        <?php endif;
}

add_action('wp_ajax_load_more_comments', 'load_more_comments');
add_action('wp_ajax_nopriv_load_more_comments', 'load_more_comments');

function load_more_comments()
{
    if (!isset($_POST['post_id']) || !isset($_POST['page'])) {
        wp_send_json_error('Invalid data');
    }

    $post_id = intval($_POST['post_id']);
    $page = intval($_POST['page']);
    $comments_per_page = get_option('comments_per_page');
    $displayed_ids = isset($_POST['displayed_ids']) ? array_map('intval', $_POST['displayed_ids']) : [];

    $args = array(
        'post_id' => $post_id,
        'number' => 0,
        'comment__not_in' => $displayed_ids,
        'status' => 'approve',
        'hierarchical' => 'false'
    );
    $comments = get_comments($args);

    if (empty($comments)) {
        wp_send_json_error('No more comments');
    }

    ob_start();
    wp_list_comments(array(
        'style' => 'ul',
        'callback' => 'mytheme_comment',
        'type' => 'all',
        'short_ping' => true,
        'max_depth' => 3
    ), $comments);
    $output = ob_get_clean();

    wp_send_json_success($output);
}

add_action('wp_enqueue_scripts', 'enqueue_load_more_comments_script');

function enqueue_load_more_comments_script()
{
    $disallowed_comment_keys_list = get_option('disallowed_keys');
    $disallowed_comment_keys_array = !empty($disallowed_comment_keys_list) ? explode("\n", $disallowed_comment_keys_list) : [];

    wp_enqueue_script('load-more-comments', get_template_directory_uri() . '/assets/js/load-more-comments.js', array('jquery'), '1.0.2', true);
    wp_localize_script('load-more-comments', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('ajax_comment_nonce'),
        'disallowed_keys' => $disallowed_comment_keys_array,
    ));

    wp_enqueue_script('ld-ajaxload', get_template_directory_uri() . '/assets/js/ajax-loadpost.js', array('jquery'), '1.0.7', true);
    $php_array = array(
        'admin_ajax' => admin_url('admin-ajax.php'),
        'load_post_nonce' => wp_create_nonce('ajax_load_post_nonce'),
    );
    wp_localize_script('ld-ajaxload', 'ld_array', $php_array);
}

function custom_default_feed_callback()
{
    add_filter('pre_option_rss_use_excerpt', '__return_zero');

    $template = locate_template('feed-rss2-custom-post-type.php');

    if (!$template) {
        $template = __DIR__ . '/custom-rss-feed/feed-rss2-custom-post-type.php';
    }

    load_template($template);
}

function custom_default_feed_flip_callback()
{
    add_filter('pre_option_rss_use_excerpt', '__return_zero');

    $template = locate_template('feed-rss2-custom-post-type-flip.php');

    if (!$template) {
        $template = __DIR__ . '/custom-rss-feed/feed-rss2-custom-post-type-flip.php';
    }

    load_template($template);
}

function custom_default_feed_sm_callback()
{
    add_filter('pre_option_rss_use_excerpt', '__return_zero');

    $template = locate_template('feed-rss2-custom-post-type-sm.php');

    if (!$template) {
        $template = __DIR__ . '/custom-rss-feed/feed-rss2-custom-post-type-sm.php';
    }

    load_template($template);
}

function change_default_feed_slug()
{
    remove_action('do_feed', 'do_feed_rss2', 10, 1);
    remove_action('do_feed_rss2', 'do_feed_rss2', 10, 1);
    remove_action('do_feed_rss', 'do_feed_rss', 10, 1);
    remove_action('do_feed_atom', 'do_feed_atom', 10, 1);

    add_feed('nb-feed', 'custom_default_feed_callback');
    add_feed('sn-feed', 'custom_default_feed_sm_callback');

    $feed = ['fb-feed','fb-feed-exercise', 'fb-feed-workouts', 'fb-feed-training', 'fb-feed-news'];

    foreach($feed as $f) {
        add_feed($f, 'custom_default_feed_flip_callback');
    }
}
add_action('init', 'change_default_feed_slug');


function ld_load_ajax($postid, $custom_query = null, $paged = 1)
{
    global $wp_query, $wp_rewrite;
    if ($custom_query)
        $main_query = $custom_query;
    else
        $main_query = $wp_query;

    $post_url = get_permalink($postid);

    $base_url = rtrim($post_url, '/') . '/page/%#%/';
    $big = 999999999;
    $total = isset($main_query->max_num_pages) ? $main_query->max_num_pages : '';
    if ($total > 1)
        echo '<div class="paginate_links">';
    echo paginate_links(array(
        'base' => $base_url,
        'format' => '?paged=%#%',
        'current' => max(1, $paged),
        'total' => $total,
        'mid_size' => '5',
        'prev_text' => __('<', 'ld'),
        'next_text' => __('>', 'ld'),
    ));
    if ($total > 1)
        echo '</div>';
}
function get_video($exercise = [], $grid1 = false)
{

    $width = 347;
    $height = 194;

    if ($grid1) {
        $width = 776;
        $height = 438;
    }
    $arrVideo = array();
    if ($exercise) {
        $arrVideo = array(
            $exercise->video_white_male,
            $exercise->video_transparent,
        );
    }

    $iframe = '';
    $video = '';
    foreach ($arrVideo as $vid) {
        if ($vid) {
            $video = $vid;
        }
    }

    if ($video) {

        $video_id = get_vimeo($video);

        if ($video_id) {
            $iframe = sprintf(
                '<iframe src="https://player.vimeo.com/video/%s?controls=1" width="%d" height="%d" frameborder="0" allow="autoplay;muted;"></iframe>',
                htmlspecialchars($video_id),
                $width,
                $height
            );
        }
    }

    return $iframe;
}
function get_vimeo($url)
{
    if (preg_match('/playback\/(\d+)\//', $url, $matches)) {
        return $matches[1];
    }
    return false;
}

add_action('wp_ajax_ajax_load_post', 'ajax_load_post_func');
add_action('wp_ajax_nopriv_ajax_load_post', 'ajax_load_post_func');
function ajax_load_post_func()
{
    global $wpdb;
    if (!wp_verify_nonce($_REQUEST['nonce'], "ajax_load_post_nonce")) {
        wp_send_json_error('None?');
    }

    $postid = $_POST['id'] ? $_POST['id'] : '';
    $filter = $_POST['filter'] ? $_POST['filter'] : 1;

    $queryE = "
    SELECT DISTINCT ee.name, ee.slug
    FROM {$wpdb->prefix}exercise_equipment_option AS eeo
    INNER JOIN {$wpdb->prefix}exercise_equipment AS ee
    ON eeo.equipment_id = ee.id
    WHERE eeo.exercise_id = %d
    ";

    $queryM = "
        SELECT DISTINCT mt.name, mt.slug
        FROM {$wpdb->prefix}exercise_primary_option AS epo
        INNER JOIN {$wpdb->prefix}exercise_muscle_anatomy AS ma
        ON epo.muscle_id = ma.id
        INNER JOIN {$wpdb->prefix}exercise_muscle_type AS mt
        ON ma.type_id = mt.id
        WHERE epo.exercise_id = %d
    ";

    $featureimg = get_field('fimg_default', 'option');

    $paged = isset($_POST['ajax_paged']) ? intval($_POST['ajax_paged']) : 1;

    if ($paged <= 0 || !$paged || !is_numeric($paged))
        wp_send_json_error('Paged?');

    $meta_keys = ['all_mt_list', 'all_ma_list', 'all_eq_list', 'all_ex_list'];

    $meta_values = array_map(function ($key) use ($postid) {
        return get_post_meta($postid, $key, true) ?: '';
    }, $meta_keys);

    list($mt_list, $ma_list, $eq_list, $ex_list) = $meta_values;

    $mt_ids = array_filter(explode(',', $mt_list));
    $ma_ids = array_filter(explode(',', $ma_list));
    $eq_ids = array_filter(explode(',', $eq_list));
    $ex_ids = array_filter(explode(',', $ex_list));

    $where_conditions = ["e.slug IS NOT NULL"];

    if (!empty($ex_ids)) {
        $ex_ids_str = implode(',', array_map('intval', $ex_ids));
        $where_conditions[] = "e.id IN ($ex_ids_str)";
    } else {
        if (!empty($mt_ids)) {
            $mt_ids_str = implode(',', array_map('intval', $mt_ids));
            $where_conditions[] = "epo.muscle_id IN (
                        SELECT id 
                        FROM {$wpdb->prefix}exercise_muscle_anatomy
                        WHERE type_id IN ($mt_ids_str)
                    )";
        }

        if (!empty($ma_ids)) {
            $ma_ids_str = implode(',', array_map('intval', $ma_ids));
            $where_conditions[] = "epo.muscle_id IN ($ma_ids_str)";
        }

        if (!empty($eq_ids)) {
            $eq_ids_str = implode(',', array_map('intval', $eq_ids));
            $where_conditions[] = "eeo.equipment_id IN ($eq_ids_str)";
        }
    }


    $listbest = get_post_meta($postid, 'best_exercise_list', true);

    if (!empty($listbest)) {
        $listbest_cleaned = implode(',', array_map('intval', explode(',', $listbest)));

        $where_conditions[] = "e.id NOT IN ($listbest_cleaned)";
    }

    $where_clause = implode(' AND ', $where_conditions);

    $query = "
            SELECT e.slug, e.id, e.description, e.name
            FROM {$wpdb->prefix}exercise AS e
            LEFT JOIN {$wpdb->prefix}exercise_primary_option AS epo ON e.id = epo.exercise_id
            LEFT JOIN {$wpdb->prefix}exercise_equipment_option AS eeo ON e.id = eeo.exercise_id
            WHERE $where_clause
            GROUP BY e.id
        ";

    $results = $wpdb->get_results($query);
    $slug_to_exercise = [];
    $slugs = [];

    foreach ($results as $exIt) {
        $slugs[] = $exIt->slug;
        $slug_to_exercise[$exIt->slug] = [
            'id' => $exIt->id,
            'description' => $exIt->description,
            'name' => $exIt->name,
        ];
    }

    if ($filter == 2) {
        sort($slugs);
    }

    $args = [
        'post_type' => 'exercise',
        'posts_per_page' => 10,
        'paged' => $paged,
        'post_name__in' => $slugs,
        'orderby' => 'post_name__in',
        'order' => "DESC"
    ];

    if ($filter == 1) {
        $args['orderby'] = 'post_views';
    }

    if ($filter == 3) {
        $args['orderby'] = 'date';
    }

    if ($filter == 4) {
        $args['orderby'] = 'comment_count';
    }

    $query_posts = new WP_Query($args);

    if ($query_posts->have_posts()):
        ob_start();
        $max_post_count = $query_posts->post_count;
        while ($query_posts->have_posts()):
            $query_posts->the_post();

            $slug = get_post_field('post_name');

            $exercise_info = isset($slug_to_exercise[$slug]) ? $slug_to_exercise[$slug] : null;

            $exercise_id = $exercise_info ? $exercise_info['id'] : '';

            $iframe = '';

            $contents = '';
            if ($exercise_id) {
                $exercise = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}exercise WHERE id = %d", $exercise_id)
                );

                $contents = $wpdb->get_results($wpdb->prepare(
                    "Select content From {$wpdb->prefix}exercise_content WHERE exercise_id = %d AND content_type = 0",
                    $exercise_id
                ), ARRAY_A);

                $iframe = '';
                if (!empty($exercise[0])) {
                    $iframe = get_video($exercise[0], true);
                }
            }

            $name = $exercise_info ? $exercise_info['name'] : '';

            $description = $exercise_info ? $exercise_info['description'] : '';

            $equipments = $wpdb->get_results($wpdb->prepare($queryE, $exercise_id));
            $mts = $wpdb->get_results($wpdb->prepare($queryM, $exercise_id));

            $featureimg = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
            ?>
                <div class="exercise__grid-item">
                    <div class="exercise__grid-item-top exercise__grid-item-top--2">
                        <h3 class="exercise__grid-item-top-content-title exercise__grid-item-top-content-title--nobd pri-color-2">
                            <a target="_blank" class="pri-color-2" href="<?= the_permalink() ?>"><?= $name ?></a>
                        </h3>
                        <div class="exercise__grid-item-top-content">
                            <div class="exercise__grid-item-top-content-video">
                                <?php if ($iframe): ?>
                                    <?= $iframe ?>
                                <?php endif; ?>
                            </div>
                            <div class="exercise__grid-item-top-content-2-column flex">
                                <div class="exercise__grid-item-top-content-em">
                                    <?php if (!empty($equipments)): ?>
                                        <div class="exercise__grid-item-top-content-equipment flex">
                                            <p class="pri-color-2">Equipment: </p>
                                            <?php foreach ($equipments as $eit): ?>
                                                <?php if ($eit->slug): ?>
                                                    <?php
                                                    $post_id = $wpdb->get_var(
                                                        $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_status = 'publish'", $eit->slug)
                                                    );

                                                    $link = '';

                                                    if ($post_id) {
                                                        $link = get_permalink($post_id);
                                                    }

                                                    if ($link):
                                                        ?>
                                                        <p class=" exercise__grid-item-top-content--text">
                                                            <a class="sec-color-3" target="_blank" href="<?= $link ?>"><?= $eit->name ?></a>
                                                        </p>
                                                    <?php else: ?>
                                                        <p class="sec-color-3 exercise__grid-item-top-content--text"><?= $eit->name ?></p>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <p class="sec-color-3 exercise__grid-item-top-content--text"><?= $eit->name ?></p>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($mts)): ?>
                                        <div class="exercise__grid-item-top-content-muscle flex">
                                            <p class="pri-color-2">Muscle: </p>
                                            <?php foreach ($mts as $tit): ?>
                                                <?php if ($tit->slug): ?>
                                                    <?php
                                                    $post_id = $wpdb->get_var(
                                                        $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_status = 'publish'", $tit->slug)
                                                    );

                                                    $link = '';

                                                    if ($post_id) {
                                                        $link = get_permalink($post_id);
                                                    }

                                                    if ($link):
                                                        ?>
                                                        <p class="sec-color-3 exercise__grid-item-top-content--text"><a class="sec-color-3"
                                                                target="_blank" href="<?= $link ?>"><?= $tit->name ?></a></p>
                                                    <?php else: ?>
                                                        <p class="sec-color-3 exercise__grid-item-top-content--text"><?= $tit->name ?></p>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <p class="sec-color-3 exercise__grid-item-top-content--text"><?= $tit->name ?></p>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="exercise__grid-item-top-content-action">
                                    <a target="_blank" class="pri-color-3" href="<?= the_permalink() ?>">View
                                        Exercise</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="exercise__grid-item-bottom exercise__grid-item-bottom--no-bg">
                        <h4>How to do</h4>
                        <?php if (!empty($contents)): ?>
                            <?= $contents[0]['content'] ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
        endwhile;
        ?>
            <?php $content = ob_get_clean(); ?>
            <?php
            ob_start();
            ld_load_ajax($postid, $query_posts, $paged);
            $pagi = ob_get_clean();
            ?>
        <?php else: ?>
            <?php wp_send_json_error('No post?'); ?>
        <?php endif; //End news
    wp_send_json_success(['content' => $content, 'pagi' => $pagi]);
    die();
}

add_action('wp_ajax_nopriv_ajax_comment', 'handle_ajax_comment');
add_action('wp_ajax_ajax_comment', 'handle_ajax_comment');

function handle_ajax_comment()
{

    $comment_data = array(
        'comment_post_ID' => intval($_POST['comment_post_ID']),
        'comment_author' => sanitize_text_field($_POST['author']),
        'comment_author_email' => sanitize_email($_POST['email']),
        'comment_content' => sanitize_textarea_field($_POST['comment']),
        'comment_type' => '',
        'comment_parent' => intval($_POST['comment_parent'])
    );

    $comment_id = wp_new_comment($comment_data);

    if ($comment_id) {
        wp_send_json_success();
    } else {
        wp_send_json_error('Error submitting comment.');
    }
}
function add_custom_product_rating_to_loop()
{
    global $product;

    $product_id = $product->get_id();

    $reviews = glsr_get_reviews([
        'assigned_posts' => $product_id,
    ]);

    $rating_count = count($reviews['reviews']);
    $ratings_sum = array_sum(array_column($reviews['reviews'], 'rating'));
    $average_rating = $rating_count > 0 ? $ratings_sum / $rating_count : 0;

    echo '<div class="custom-product-rating">';

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $average_rating) {
            echo '<span class="star filled">&#9733;</span>';
        } else {
            echo '<span class="star">&#9734;</span>';
        }
    }

    if ($rating_count > 0) {
        echo '<span class="rating-count">(' . $rating_count . ')</span>';
    } else {
        echo '<span class="rating-count">(0)</span>';
    }

    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item_title', 'add_custom_product_rating_to_loop', 5);



function add_custom_class_to_price($price_html, $product)
{
    $price_html = preg_replace('/(<span class="woocommerce-Price-amount amount">)/', '<span class="woocommerce-Price-amount amount regular_price">', $price_html, 1);
    return $price_html;
}
add_filter('woocommerce_get_price_html', 'add_custom_class_to_price', 10, 2);

function feed_item_title()
{
    add_meta_box(
        'feed_title',
        'Feed Item Title',
        'feed_title_field',
        ['informational_posts', 'exercise'], // Array of CPTs
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'feed_item_title');


function feed_title_field($post)
{
    $value = get_post_meta($post->ID, '_feed_title', true);
    ?>

        <label for="feed_title">Feed Title Option:</label>
        <div class="">
            <input type="text" name="feed_title" data-default="<?=$value?>" id="feedTitle" value="<?=$value?>" style="width: 50%; margin-top: 20px">
        </div>
        <?php
}

function save_post_custome($post_id)
{

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['feed_title'])) {
        $feed_title = sanitize_text_field($_POST['feed_title']);
        update_post_meta($post_id, '_feed_title', $feed_title);
    }

    $post = get_post($post_id);
    if ($post->post_status === 'publish' && $post->comment_status !== 'open') {
        wp_update_post(array(
            'ID' => $post_id,
            'comment_status' => 'open',
        ));
    }
    
}
add_action('save_post', 'save_post_custome');

?>