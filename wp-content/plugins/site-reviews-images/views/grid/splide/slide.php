<?php defined('WPINC') || die; ?>

<div class="splide__slide" data-id="{{ ID }}" data-index="{{ index }}" data-review_id="{{ review_id }}">
  <figure>
    <img src="{{ src }}" srcset="{{ srcset }}" sizes="{{ sizes }}" alt="{{ caption }}" />
    <?php if (!in_array('caption', $hide) && !empty($context['caption'])) : ?><figcaption>{{ caption }}</figcaption><?php endif; ?>
  </figure>
</div>
