<?php

$postid = get_the_ID();
the_post();
$listBest = get_post_meta($postid, 'best_exercise_list', true);

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
?>
<div class="top-best">
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
                                    <h3 class="exercise__grid-item-top-content-title"><a
                                            class="pri-color-2 text-special clamp-1" target="_blank"
                                            href="<?= home_url('/exercise/' . $exercise[0]->slug) ?>"><?= $exercise[0]->name ?></a>
                                    </h3>
                                    <div class="exercise__grid-item-top-content-video">
                                        <?php if ($iframe): ?>
                                            <?= $iframe ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
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
        <?php

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

        if (!empty($listBest)) {
            $listbest_str = implode(',', array_map('intval', $listBest));
            $where_conditions[] = "e.id NOT IN ($listbest_str)";
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
            'paged' => $paged,
            'post_name__in' => $slugs,
            'orderby' => 'post_views',
            'posts_per_page' => -1,
        ];

        $query_posts = new WP_Query($args);
        if ($query_posts->have_posts()):
            ?>
            <div class="exercise__grid exercise__grid--1 grid exercise-counter">
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
                            </div>
                        </div>
                        <div class="exercise__grid-item-bottom exercise__grid-item-bottom--no-bg">
                            <?php if (!empty($contents)): ?>
                                <?php
                                $content = $contents[0]['content'];
                                ?>
                                <?= $content ?>
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