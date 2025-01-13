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
    <div class="top-best">
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
                    <h1 class="pri-color-3"><?= the_title() ?></h1>
                    <?php $aname = get_field('user_nshort', 'user_' . $upid);
                    if (!$aname || $aname == '')
                        $aname = get_the_author();
                    ?>
                    <div class="single-author mr-bottom-20">
                        <div class="name-author">
                            <div class="info">
                                <div class="author-by pri-color-3" itemscope>
                                    <time class="updated has-small-font-size"
                                        datetime="<?php the_modified_date('c'); ?>" itemprop="dateModified"><?php
                                          if (get_the_modified_date('U') !== get_the_date('U')) {
                                              echo __('Updated on', 'hc_theme');
                                          } else {
                                              echo __('Published', 'hc_theme');
                                          }
                                          ?>
                                        <?php the_modified_date('F d, Y'); ?></time>
                                    <span class="has-small-font-size pri-color-3">- Writen by: </span>
                                    <span class="has-small-font-size pri-color-3" itemprop="author" itemscope
                                        itemtype="https://schema.org/Person"><a class="pri-color-3" target="_blank"
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
                                                <a target="_blank" class="pri-color-3" style="text-decoration: underline"
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
                            <p class="has-small-font-size pri-color-3" style="margin-bottom: 0">Follow us: </p>
                            <?php foreach ($socials as $social): ?>
                                <a target="_blank" href="<?php echo $social['link']; ?>"><img
                                        alt="<?= $social['icon']['alt']; ?>" src="<?= $social['icon']['url']; ?>" /></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <p class="pri-color-3 text-center"><?= $heroCalculator ?></p>
            </div>
        </section>

        <?php if ($listBest): ?>
            <section class="best">
                <div class="best__container container">
                    <?php
                    $bestTitle = get_field('best_title', $postid);
                    ?>
                    <h2 class="best__title pri-color-3"><?= $bestTitle ?: 'Best Exercise' ?></h2>
                    <div class="exercise__grid exercise__flex--3 flex">
                        <?php
                        $listBest = explode(',', $listBest);
                        foreach ($listBest as $id):
                            $exercise = $wpdb->get_results(
                                $wpdb->prepare("SELECT * FROM {$wpdb->prefix}exercise WHERE id = %d", $id)
                            );

                            $iframe = '';
                            if (!empty($exercise[0])) {
                                $iframe = get_video($exercise[0]);
                            }
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
                                        <div class="exercise__grid-item-top-content-video">
                                            <?php if ($iframe): ?>
                                                <?= $iframe ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="exercise__grid-item-bottom">
                                    <h3 class="exercise__grid-item-top-content-title"><a
                                            class="pri-color-2 text-special clamp-1" target="_blank"
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
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <section class="all best-ajax-section">
        <div class="all__container container exc-container">
            <h2 class="all__title pri-color-2 text-center">
                <?= get_field('all_exercise_title', $postid) ?: 'All Triceps Exercises' ?>
            </h2>
            <p class="pri-color-2"><?= get_field('all_exercise_description', $postid); ?></p>
            <div class="all__flex flex">
                <div class="all__flex-item all__flex-item--trending all__flex-item--active text-center filter-active"
                    data-filter="1">
                    <p class="has-medium-font-size pri-color-2">Trending</p>
                </div>
                <div class="all__flex-item all__flex-item--character text-center" data-filter="2">
                    <p class="has-medium-font-size pri-color-2">A-Z</p>
                </div>
                <div class="all__flex-item all__flex-item--analysis text-center" data-filter="3">
                    <p class="has-medium-font-size pri-color-2">Analysis</p>
                </div>
                <div class="all__flex-item all__flex-item--discusstion text-center" data-filter="4">
                    <p class="has-medium-font-size pri-color-2">Discussion</p>
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
                <div class="exercise__grid exercise__grid--1 grid mr-bottom-20">
                    <?php
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
                                "Select content From {$wpdb->prefix}exercise_content WHERE exercise_id = %d AND content_type = 1",
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
                                <h3
                                    class="exercise__grid-item-top-content-title exercise__grid-item-top-content-title--nobd pri-color-2">
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
                                                        <p class="sec-color-3 exercise__grid-item-top-content--text"><?= $eit->name ?>
                                                        </p>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($mts)): ?>
                                                <div class="exercise__grid-item-top-content-muscle flex">
                                                    <p class="pri-color-2">Muscle: </p>
                                                    <?php foreach ($mts as $tit): ?>
                                                        <p class="sec-color-3 exercise__grid-item-top-content--text"><?= $tit->name ?>
                                                        </p>
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

    <section class="exc-section-content single-main">
        <div class="container">
            <div class="exc-container bd-bot">
                <?php the_content(); ?>
                <?php
                if (get_field('enable_source', 'option') == true) {
                    ?>
                    <div class="sg-resources mr-bottom-20 pd-main">
                        <h3 class="pri-color-2">Resources</h3>
                        <div class="intro">
                            <?= get_field('source_intro', 'option'); ?>
                        </div>
                        <?php $source_content = get_field('source_content', $postid);
                        if ($source_content)
                            echo $source_content;
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php
    $author_id = get_post_field('post_author', $postid);

    $author_name = get_the_author_meta('display_name', $author_id);
    $author_url = get_author_posts_url($author_id);

    $avt = '';

    if (get_field('new_avata', 'user_' . $author_id)) {
        $avt = get_field('new_avata', 'user_' . $author_id);
    } elseif (get_field('avata', 'user_' . $author_id)) {
        $avt = get_field('avata', 'user_' . $author_id);
    }

    $user_description = '';

    if (get_field('new_story', 'user_' . $author_id)) {
        $user_description = get_field('new_story', 'user_' . $author_id);
    } elseif (get_field('story', 'user_' . $author_id)) {
        $user_description = get_field('story', 'user_' . $author_id);
    }

    $userPosition = get_field('position', 'user_' . $author_id);

    if (get_field('new_position', 'user_' . $author_id)) {
        $userPosition = get_field('new_position', 'user_' . $author_id);
    } elseif (get_field('position', 'user_' . $author_id)) {
        $userPosition = get_field('position', 'user_' . $author_id);
    }
    ?>
    <div class="single-main exc-author">
        <aside class="single-sidebar ">
            <div class="container">
                <div class="author-about exc-container">
                    <h3 class="pri-color-2">About the Author</h3>
                    <div class="author-write">
                        <div class="author-link">
                            <?php
                            if ($avt) {
                                ?>
                                <a target="_blank" href="<?php echo $author_url; ?>"><img src="<?php echo $avt; ?>"
                                        alt=""></a>
                            <?php } else { ?>
                                <a target="_blank" href="<?php echo $author_url; ?>"><img
                                        src="<?php echo get_field('avatar_default', 'option'); ?>" alt="">
                                <?php } ?>
                                <p class="has-medium-font-size"><a target="_blank"
                                        style="color: var(--pri-color-2) !important;"
                                        href="<?php echo $author_url; ?>"><?php the_author(); ?>
                                    </a>
                                    <?php if ($userPosition): ?>
                                        <span>
                                            <?= $userPosition; ?>
                                            </sp>
                                        <?php endif; ?>
                                </p>
                        </div>
                        <?php if ($user_description) { ?>
                            <div class="author-info">
                                <p><?php echo wp_trim_words($user_description, 50, '') . '.. '; ?><a
                                        href="<?php echo $author_url; ?>"> See more</a></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    <?php if (comments_open()): ?>
        <div class="comments-section">
            <div class="container">
                <?php comments_template(); ?>
            </div>
        </div>
    <?php endif; ?>
</main>
<?php get_footer(); ?>