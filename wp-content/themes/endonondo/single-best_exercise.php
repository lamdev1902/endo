<?php

$postid = get_the_ID();
$postID = get_the_ID();
$post_terms = wp_get_post_terms($postid, 'category');
$author_id = get_post_field('post_author', $postid);
$upid = get_post_field('post_author', $postid);
$author_name = get_the_author_meta('display_name', $author_id);
$author_url = get_author_posts_url($author_id);
$user_info = get_userdata($author_id);

$checktime = '';

$disableFeature = get_field('disable_featured_image', $postid);

$advertiser_disclosure = get_field('enable_tooltip1', $postid);

$enable_fat_checked = get_field('enable_fat_checked', $postid);

$enable_fcgroup = get_field('enable_fcgroup', $postid);

get_header();
the_post();
$post_type = $post->post_type;

$listBest = get_post_meta($postid, 'best_exercise_list', true);

$queryE = "
    SELECT DISTINCT ee.name 
    FROM {$wpdb->prefix}exercise_equipment_option AS eeo
    INNER JOIN {$wpdb->prefix}exercise_equipment AS ee
    ON eeo.equipment_id = ee.id
    WHERE eeo.exercise_id = %d
";

$queryM = "
    SELECT DISTINCT mt.name
    FROM {$wpdb->prefix}exercise_primary_option AS epo
    INNER JOIN {$wpdb->prefix}exercise_muscle_anatomy AS ma
    ON epo.muscle_id = ma.id
    INNER JOIN {$wpdb->prefix}exercise_muscle_type AS mt
    ON ma.type_id = mt.id
    WHERE epo.exercise_id = %d
";

$featureimg = get_field('fimg_default', 'option');

?>
<main id="content">
    <?php
    $heroCalculator = get_field('hero_description', $postid);
    ?>
    <section class="hero mb">
        <div class="page-top-white mb-top-black">
            <div class="container">
                <?php
                if (function_exists('yoast_breadcrumb')) {
                    yoast_breadcrumb('<div id="breadcrumbs" class="breacrump">', '</div>');
                }
                ?>
            </div>
        </div>
        <div class="hero__container container">
            <div class="text-center special-width">
                <h1><?= the_title() ?></h1>
                <?php $aname = get_field('user_nshort', 'user_' . $upid);
                if (!$aname || $aname == '')
                    $aname = get_the_author();
                ?>
                <div class="single-author mr-bottom-20">
                    <div class="name-author">
                        <div class="info">
                            <div class="author-by" itemscope>
                                <time class="updated has-small-font-size" datetime="<?php the_modified_date('c'); ?>"
                                    itemprop="dateModified"><?php
                                    if (get_the_modified_date('U') !== get_the_date('U')) {
                                        echo __('Updated on', 'hc_theme');
                                    } else {
                                        echo __('Published', 'hc_theme');
                                    }
                                    ?>
                                    <?php the_modified_date('F d, Y'); ?></time>
                                <span class="has-small-font-size">- Writen by: </span>
                                <span class="has-small-font-size" itemprop="author" itemscope
                                    itemtype="https://schema.org/Person"><a class="pri-color-2" target="_blank"
                                        href="<?php echo $author_url; ?>"
                                        title="<?php echo __('View all posts by', 'hc_theme'); ?> <?php the_author(); ?>"
                                        rel="author" itemprop="url"><span class="ncustom has-small-font-size"
                                            itemprop="name"><?php echo $aname; ?></span></a></span>
                                <?php
                                $medically_reviewed = get_field('select_author', $postid);
                                if ($medically_reviewed) { ?>
                                    <span class="has-small-font-size"> - Reviewed by</span>
                                    <span class="has-small-font-size">
                                        <?php foreach ($medically_reviewed as $m => $mr) {
                                            $anamer = get_field('user_nshort', 'user_' . $mr['ID']);
                                            if (!$anamer || $anamer == '')
                                                $anamer = $mr['display_name'];
                                            ?>
                                            <a target="_blank" class="pri-color-2" style="text-decoration: underline"
                                                href="<?php echo get_author_posts_url($mr['ID']); ?>"><?php if ($m > 0)
                                                       echo ' ,'; ?><?php echo $anamer; ?></a>
                                        <?php } ?>
                                    </span>
                                <?php } ?>
                                <?php
                                if ($enable_fcgroup): ?>
                                    <?php if ($enable_fcgroup == '1') { ?>
                                        <span id="at-box"><img
                                                src="<?php echo get_template_directory_uri(); ?>/assets/images/author.svg"
                                                alt="Fact checked"></span>
                                    <?php } elseif ($enable_fcgroup == '2') { ?>
                                        <span id="eb-box"><img
                                                src="<?php echo get_template_directory_uri(); ?>/assets/images/eb.svg"
                                                alt="Fact checked"></span>
                                    <?php } ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $socials = get_field('follow_social', 'option');
                if ($socials):
                    ?>
                    <div class="social hero__social flex mr-bottom-20 text-center">
                        <p class="has-small-font-size" style="margin-bottom: 0">Follow us: </p>
                        <?php foreach ($socials as $social): ?>
                            <a target="_blank" href="<?php echo $social['link']; ?>"><img alt="<?= $social['icon']['alt']; ?>"
                                    src="<?= $social['icon']['url']; ?>" /></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <p><?= $heroCalculator ?></p>
        </div>
    </section>

    <?php if ($listBest): ?>
        <section class="best">
            <div class="best__container container">
                <h2 class="best__title">Best Exercise</h2>
                <div class="exercise__grid exercise__grid--3 grid">
                    <?php
                    $listBest = explode(',', $listBest);
                    foreach ($listBest as $id):
                        $exercise = $wpdb->get_results(
                            "SELECT * From {$wpdb->prefix}exercise WHERE id = $id"
                        );

                        $equipment_names = $wpdb->get_results($wpdb->prepare($queryE, $id));

                        $muscle_type = $wpdb->get_results($wpdb->prepare($queryM, $id));

                        $post = url_to_postid(home_url('/exercise/' . $exercise[0]->slug));

                        if ($post) {
                            $featureimg = wp_get_attachment_url(get_post_thumbnail_id($post));
                        }
                        ?>
                        <div class="exercise__grid-item">
                            <div class="exercise__grid-item-top exercise__grid-item-top--3">
                                <div class="exercise__grid-item-top-content">
                                    <div class="exercise__grid-item-top-content-img">
                                        <a target="_blank" href="<?= home_url('/exercise/' . $exercise[0]->slug) ?>">
                                            <img src="<?= $featureimg ?>" alt="">
                                        </a>
                                    </div>
                                    <?php if (!empty($equipment_names)): ?>
                                        <div class="exercise__grid-item-top-content-equipment flex">
                                            <?php foreach ($equipment_names as $eit): ?>
                                                <p class="pri-color-3 text-special clamp-1"><?= $eit->name ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="exercise__grid-item-top-content-title"><a class="pri-color-3" target="_blank"
                                            href="<?= home_url('/exercise/' . $exercise[0]->slug) ?>"><?= $exercise[0]->name ?></a>
                                    </h3>
                                    <?php if (!empty($muscle_type)): ?>
                                        <div class="exercise__grid-item-top-content-muscle flex">
                                            <?php foreach ($muscle_type as $tit): ?>
                                                <p><?= $tit->name ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="exercise__grid-item-bottom">
                                <p><?php echo preg_replace('/\.(?!\s)/', '. ', wp_trim_words($exercise[0]->description, 100, '')) . '... '; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="all best-ajax-section">
        <div class="all__container container">
            <h2 class="all__title">All Triceps Exercises</h2>
            <div class="all__flex flex">
                <div class="all__flex-item all__flex-item--trending all__flex-item--active text-center filter-active" data-filter="1">
                    <p class="has-medium-font-size">Trending</p>
                </div>
                <div class="all__flex-item all__flex-item--character text-center" data-filter="2">
                    <p class="has-medium-font-size">A-Z</p>
                </div>
                <div class="all__flex-item all__flex-item--analysis text-center" data-filter="3">
                    <p class="has-medium-font-size">Analysis</p>
                </div>
                <div class="all__flex-item all__flex-item--discusstion text-center" data-filter="4">
                    <p class="has-medium-font-size">Discussion</p>
                </div>
            </div>
            <?php
            $posts_per_page = 10;

            $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;

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

            $args = [
                'post_type' => 'exercise',
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post_name__in' => $slugs,
                'orderby' => 'post_views'
            ];
            $query_posts = new WP_Query($args);
            if ($query_posts->have_posts()):
                ?>
                <div class="exercise__grid exercise__grid--2 grid mr-bottom-20">
                    <?php
                    while ($query_posts->have_posts()):
                        $query_posts->the_post();
                        $slug = get_post_field('post_name');

                        $exercise_info = isset($slug_to_exercise[$slug]) ? $slug_to_exercise[$slug] : null;

                        $exercise_id = $exercise_info ? $exercise_info['id'] : '';
                        $name = $exercise_info ? $exercise_info['name'] : '';
                        $description = $exercise_info ? $exercise_info['description'] : '';

                        $equipments = $wpdb->get_results($wpdb->prepare($queryE, $exercise_id));
                        $mts = $wpdb->get_results($wpdb->prepare($queryM, $exercise_id));

                        $featureimg = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                        ?>
                        <div class="exercise__grid-item">
                            <div class="exercise__grid-item-top exercise__grid-item-top--2">
                                <div class="exercise__grid-item-top-content">
                                    <div class="exercise__grid-item-top-content-img">
                                        <a target="_blank" href="<?= the_permalink(); ?>">
                                            <img src="<?= $featureimg ?>" alt="">
                                        </a>
                                    </div>
                                    <?php if (!empty($equipments)): ?>
                                        <div class="exercise__grid-item-top-content-equipment flex">
                                            <?php foreach ($equipments as $eit): ?>
                                                <p class="pri-color-3 text-special clamp-1"><?= $eit->name ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="exercise__grid-item-top-content-title">
                                        <a class="pri-color-3" href="<?= the_permalink() ?>"><?= $name ?></a>
                                    </h3>
                                    <?php if (!empty($mts)): ?>
                                        <div class="exercise__grid-item-top-content-muscle flex">
                                            <?php foreach ($mts as $tit): ?>
                                                <p><?= $tit->name ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="exercise__grid-item-bottom">
                                <p><?php echo preg_replace('/\.(?!\s)/', '. ', wp_trim_words($description, 100, '')) . '... '; ?></p>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    ?>
                </div>
                <div class="pagination-best text-center">
                    <?php
                    ld_load_ajax($postid, $query_posts);
                    ?>
                </div>
                <?php
                wp_reset_postdata();
                ?>
                <input type="hidden" id="postID" name="postid" value="<?= $postid ?>">
                <?php
            endif;
            ?>
            <div class="exercise__grid-loading">
                <div class="exercise__grid-loading-item"></div>
            </div>
        </div>
    </section>
    <?php
    $app = get_field('intro_app', $postid);
    if (!empty($app[0])):
        $app = $app[0];
        $storeLink = !empty($app['store']) ? $app['store'] : '';
        $explore = !empty($app['explore']) ? $app['explore'] : '';
        $store = $storeLink ?: '';
        $logo = get_field('enfit_logo', 'option');
        ?>

        <section class="app mb">
            <div class="app__container container">
                <div class="app__content">
                    <?php if ($logo): ?>
                        <div class="app__content-logo">
                            <img src="<?= $logo ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($app['title'])): ?>
                        <h1><?= $app['title'] ?></h1>
                    <?php endif; ?>
                    <?php if (!empty($app['description'])): ?>
                        <p><?= $app['description'] ?></p>
                    <?php endif; ?>
                    <div class="app__content-action enfit-action mr-bottom-20 flex">
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
</main>
<?php get_footer(); ?>