<?php defined('ABSPATH') || die; ?>

<div class="glsr-reviews-splide">
    <div class="gl-swiper-container">
        <div class="splide gl-splide gl-carousel is-overflow" data-splide='{{ options }}'>
            <div class="splide__track">
                <div class="splide__list gl-swiper-wrapper {{ class }}" data-reviews>
                    {{ reviews }}
                </div>
            </div>
        </div>
    </div>
    {{ pagination }}
</div>
