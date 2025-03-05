<?php
/* Template Name: On Coaching*/ $pageid = get_the_ID();
get_header(); ?>
<main id="content" class="oncoaching">
    <section class="hero mb  mr-bottom-20">
        <div class="page-top-white mb-top-black">
            <div class="container">
                <?php
                if (function_exists('yoast_breadcrumb')) {
                    yoast_breadcrumb('<div id="breadcrumbs" class="breacrump">', '</div>');
                }
                ?>
            </div>
        </div>
    </section>
    <?php
    $coachVideo = get_field('coachhero_video', $pageid);
    $coachDescription = get_field('coachhero_description', $pageid);
    $imgFirst = get_field('coachhero_image_first', $pageid);
    $imgSecond = get_field('coachhero_image_second', $pageid);
    ?>
    <section class="coachhero">
        <div class="coachhero__container">
            <div class="coachhero__top flex">
                <div class="coachhero__text">
                    <div class="coachhero__logo">
                        <img width="274" height="40"
                            src="<?= get_template_directory_uri() . '/assets/image/oncoach-logo.svg' ?>" alt="">
                    </div>
                    <h1 class="pri-color-3 has-x-large-font-size"><?= the_title() ?></h1>
                    <?php
                    $socials = get_field('follow_social', 'option');
                    if ($socials):
                        ?>
                        <div class="social">
                            <p class="has-small-font-size pri-color-3" style="margin-bottom: 0">Follow us: </p>
                            <?php foreach ($socials as $social): ?>
                                <a target="_blank" href="<?php echo $social['link']; ?>"><img
                                        alt="<?= $social['icon']['alt']; ?>" src="<?= $social['icon']['url']; ?>" /></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="coachhero__action">
                        <a href="#">DISCOVER NOW</a>
                    </div>
                </div>
                <div class="coachhero__video">
                    <?php
                    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $coachVideo, $matches)) {
                        $video_id = $matches[1];
                        $embed_url = "https://www.youtube.com/embed/" . $video_id;
                    } else {
                        $embed_url = '';
                    }
                    ?>
                    <iframe width="594" height="416" src="<?= $embed_url ?>" frameborder="0"
                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="coachhero__bottom flex">
                <div class="coachhero__description pri-color-3">
                    <?= $coachDescription ?>
                </div>
                <div class="coachhero__img">
                    <div class="coachhero__image-first">
                        <?php if ($imgFirst):
                            $src = esc_url($imgFirst['url']);
                            $alt = esc_attr($imgFirst['alt']);
                            $srcset = esc_attr(wp_get_attachment_image_srcset($imgFirst['ID']));
                            $sizes = esc_attr(wp_get_attachment_image_sizes($imgFirst['ID']));
                            ?>
                            <img src="<?= $src ?>" alt="<?= $alt ?>" decoding="async" srcset=<?= $srcset ?>
                                sizes="<?= $sizes ?>">
                        <?php endif; ?>
                    </div>
                    <div class="coachhero__image-second">
                        <?php if ($imgFirst):
                            $src = esc_url($imgFirst['url']);
                            $alt = esc_attr($imgFirst['alt']);
                            $srcset = esc_attr(wp_get_attachment_image_srcset($imgFirst['ID']));
                            $sizes = esc_attr(wp_get_attachment_image_sizes($imgFirst['ID']));
                            ?>
                            <img src="<?= $src ?>" alt="<?= $alt ?>" decoding="async" srcset=<?= $srcset ?>
                                sizes="<?= $sizes ?>">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <?php
    $coachboxTitle = get_field('coachbox_title', $pageid);
    $coachboxItem = get_field('coachbox_item', $pageid);
    if ($coachboxItem):
        ?>
        <section class="coachbox">
            <div class="container">
                <h2><?= $coachboxTitle ?></h2>
                <div class="coachbox__list grid grid-ex">
                    <?php foreach ($coachboxItem as $key => $cb): ?>
                        <?php
                        $index = $key + 1;
                        $is_white = in_array($index % 4, [1, 0]);
                        ?>
                        <div class="coachbox__item <?= $is_white ? 'coachbox__item--white' : '' ?>">
                            <div class="coachbox__icon">
                                <img src="<?= $cb['coachbox_logo'] ?>" width="30" height="30" alt="">
                            </div>
                            <div class="coachbox__title">
                                <p class="has-medium-font-size"><?= $cb['coachbox_title'] ?></p>
                            </div>
                            <div class="coachbox__description">
                                <p><?= $cb['coachbox_description'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php $coachuserList = get_field('coachuser_list', $pageid);
    if ($coachuserList):
        ?>
        <section class="coachuser">
            <div class="container">
                <div class="coachuser__content">
                    <h2 class="text-center pri-color-3"><?= get_field('coachuser_title', $pageid); ?></h2>
                    <div class="coachuser__list">
                        <?php foreach ($coachuserList as $cuser): ?>
                            <div class="coachuser__item flex">
                                <div class="coachuser__item-left">
                                    <div class="coachuser__name">
                                        <p class="has-large-font-size pri-color-3">
                                            <?= $cuser['coachuser_name'] ?>
                                        </p>
                                    </div>
                                    <div class="coachuser__description">
                                        <p class="pri-color-3"><?= $cuser['coachuser_description'] ?></p>
                                    </div>
                                    <?php if (!empty($cuser['coachuser_fitnesslist'])): ?>
                                        <div class="coachuser__fitnesslist">
                                            <?php foreach ($cuser['coachuser_fitnesslist'] as $fnItem): ?>
                                                <div class="coachuser__fitnessitem flex">
                                                    <img width="24" height="24"
                                                        src="<?= get_template_directory_uri() . '/assets/images/fitness-coach.svg' ?>"
                                                        alt="">
                                                    <p class="pri-color-3"><strong><?= $fnItem['coachuser_fitness'] ?></strong></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="coachuser__item-right">
                                    <div class="coachuser__avt">
                                        <img src="<?= $cuser['coachuser_avt'] ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php
    $coachplanList = get_field('coachplan_list', $pageid);
    if ($coachplanList):
        ?>
        <section class="coachplan single-main">
            <div class="container">
                <div class="coachplan__list">
                    <?php foreach ($coachplanList as $key => $pl): ?>
                        <div class="coachplan__item flex <?= $key % 2 != 0 ? 'coachplan__item--special' : '' ?>">
                            <div class="coachplan__content">
                                <?= $pl['coachplan_text'] ?>
                            </div>
                            <div class="coachplan__image">
                                <img src="<?= $pl['coachplan_image']; ?>" alt="">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php $coachtestList = get_field('coachtest_list', $pageid);
    if ($coachtestList):
        ?>
        <section class="coachtest">
            <div class="container">
                <div class="coachtest__content">
                    <h2 class=" text-center pri-color-3"><?= get_field('coachtest_title'); ?></h2>
                    <div class="coachtest__list flex">
                        <?php foreach ($coachtestList as $ct): ?>
                            <div class="coachtest__item text-center">
                                <div class="coachtest__statistics">
                                    <p class="has-x-large-font-size pri-color-3"><?= $ct['coachtest_statistics']; ?></p>
                                    <p class="pri-color-3"><?= $ct['coachtest_statistics_text']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php $coachEvaList = get_field('coachcmt_list', $pageid);
            if ($coachEvaList):
                ?>
                <div class="coachcmt__block">
                    <div class="coachcmt__list flex">
                        <?php foreach ($coachEvaList as $eval): ?>
                            <div class="coachcmt__item">
                                <p class="has-large-font-size coachcmt__name"><?= $eval['coachcmt__name'] ?></p>
                                <p class="coachcmt__position"><?= $eval['coachcmt__position'] ?></p>
                                <p class="coachcmt__description"><?= $eval['coachcmt__description'] ?></p>
                                <input type="hidden" value="<?= $eval['coachcmt__rate'] ?>" class="coachcmt__rate"
                                    data-empty="fa-regular fa-star" data-filled="fas fa-star" data-fractions="1" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>
    <section class="single-main">
        <div class="container">
            <?php the_content(); ?>
        </div>
    </section>
    <?php
    $calendyStep = get_field('coachcalendy__step', $pageid);
    ?>
    <section class="coachcalendy">
        <div class="container">
            <h2 class="text-center pri-color-3"><?= get_field('coachcalendy_title', $pageid); ?></h2>
            <p class="text-center pri-color-3"><?= get_field('coachcalendy_description', $pageid); ?></p>
            <?php if ($calendyStep): ?>
                <div class="coachcalendy__step flex">
                    <?php foreach ($calendyStep as $step): ?>
                        <div class="coachcalendy__item">
                            <div class="coachcalendy__icon">
                                <img width="30" height="30" src="<?= $step['icon'] ?>" alt="">
                            </div>
                            <p class="has-medium-font-size pri-color-3"><?= $step['title'] ?></p>
                            <p class="pri-color-3"><?= $step['description'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="coachcalendy__calender">
                <div class="calendly-inline-widget" data-url="https://calendly.com/endomondo-info/new-meeting"
                    style="min-width:420px;height:587px;min-height:550px"></div>
            </div>
            <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
        </div>
    </section>
</main>
<?php get_footer(); ?>