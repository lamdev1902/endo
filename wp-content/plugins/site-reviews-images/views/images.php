<?php defined('WPINC') || die; ?>

<?php foreach ($attachments as $attachment) : ?>
    <a href="<?= $attachment->large_src; ?>"
        class="glsr-image<?php if ('lightbox' === $modal) : ?> spotlight<?php endif; ?>"
        data-id="<?= $attachment->ID; ?>"
        data-height="<?= $attachment->large_height; ?>"
        data-width="<?= $attachment->large_width; ?>"
        <?php if ('modal' === $modal) : ?>
            data-caption="<?= $attachment->caption; ?>"
            data-elementor-open-lightbox="no"
            data-glsr-trigger="glsr-modal-image"
        <?php endif; ?>
        <?php if ('lightbox' === $modal) : ?>
            data-control="close,next,page,prev,theme,zoom"
            data-description="<?= $attachment->caption; ?>"
            data-elementor-open-lightbox="no"
            data-title="false"
        <?php endif; ?>
    >
        <img src="<?= $attachment->src; ?>" width="<?= $attachment->width; ?>" height="<?= $attachment->height; ?>" alt="<?= $attachment->caption; ?>" loading="lazy">
    </a>
<?php endforeach; ?>
