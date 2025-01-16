<?php defined('ABSPATH') || die; ?>

<div class="glsr-reviews-swiper">
    <div class="gl-swiper-container">
        <div class="gl-swiper gl-carousel" data-swiper='{{ options }}'>
            <div class="gl-swiper-wrapper {{ class }}" data-reviews>
                {{ reviews }}
            </div>
            <div class="gl-swiper-arrows">
                <div class="gl-swiper-arrow gl-swiper-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="currentColor"><path d="M14.447 21.004l7.214 7.057c.24.246.533.363.881.363.708 0 1.277-.559 1.277-1.258 0-.354-.15-.67-.402-.922l-6.414-6.246 6.414-6.24c.254-.254.402-.574.402-.919 0-.702-.569-1.258-1.277-1.258-.351 0-.641.117-.881.357l-7.214 7.06c-.31.3-.444.62-.447 1.003s.135.698.447 1.004z"/></svg>
                </div>
                <div class="gl-swiper-arrow gl-swiper-next">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="currentColor"><path d="M14.447 21.004l7.214 7.057c.24.246.533.363.881.363.708 0 1.277-.559 1.277-1.258 0-.354-.15-.67-.402-.922l-6.414-6.246 6.414-6.24c.254-.254.402-.574.402-.919 0-.702-.569-1.258-1.277-1.258-.351 0-.641.117-.881.357l-7.214 7.06c-.31.3-.444.62-.447 1.003s.135.698.447 1.004z"/></svg>
                </div>
            </div>
            <div class="gl-swiper-pagination"></div>
        </div>
    </div>
    {{ pagination }}
</div>
