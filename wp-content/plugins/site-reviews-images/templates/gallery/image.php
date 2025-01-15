<?php defined('WPINC') || die; ?>

<a class="glsri-image glsri-aspect-ratio"
    href="{{ large.src }}"
    data-elementor-open-lightbox="no"
    data-glsr-trigger="glsr-modal-gallery"
    data-height="{{ large.height }}"
    data-id="{{ ID }}"
    data-index="{{ index }}"
    data-review_id="{{ review_id }}"
    data-width="{{ large.width }}"
    tabindex="0"
>
    <img class="glsri-absolute-fill" loading="lazy"
        src="{{ medium.src }}" 
        width="{{ medium.width }}" 
        height="{{ medium.height }}" 
        alt="" 
    >
</a>
