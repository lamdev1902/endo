<?php
$ads = get_field('ads_enfit', 'option');
$intro = get_field('intro_app', 'option');
$storeLink = get_field('ads_store', 'option');
$explore = !empty($intro[0]['explore']) ? $intro[0]['explore'] : '';
$store = $storeLink ?: '';
if ($ads):
    ?>
    <div class="ads_list">
        <?php foreach ($ads as $ad): ?>
            <div class="ads-enfit">
                <div class="ads__content flex">
                    <div class="ads__left">
                        <div class="ads__left--logo">
                            <img src="<?= get_field('enfit_logo', 'option') ?>" alt="">
                        </div>
                        <div class="ads__left--content">
                            <h2>
                                <?= $ad['title'] ?>
                            </h2>
                            <p class="has-small-font-size"><?= $ad['description'] ?></p>
                            <div class="enfit-action flex">
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
                    <div class="ads__right">
                        <div class="ads__right--img">
                            <img src="<?= $ad['image'] ?>" alt="">
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>