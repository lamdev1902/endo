<?php defined('WPINC') || die; ?>

<?php foreach ($images as $image) : ?>
<a class="glsri-image glsri-aspect-ratio"
    href="<?= $image->large()->src; ?>"
    data-elementor-open-lightbox="no"
    data-height="<?= $image->large()->height; ?>"
    data-id="<?= $image->ID; ?>"
    data-index="<?= $image->index; ?>"
    data-review_id="<?= $image->review_id; ?>"
    data-width="<?= $image->large()->width; ?>"
    tabindex="0"
>
    <img loading="lazy" class="glsri-absolute-fill"
        src="<?= $image->medium()->src; ?>" 
        width="<?= $image->medium()->width; ?>" 
        height="<?= $image->medium()->height; ?>" 
        alt="" 
    >
</a>
<?php endforeach; ?>
