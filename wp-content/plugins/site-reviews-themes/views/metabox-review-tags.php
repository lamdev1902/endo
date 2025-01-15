<?php defined('WPINC') || die; ?>

<div id="glsrt-review-tags" class="glsrt-review-tags">
    <div>
        <select name="form">
            <?php foreach ($forms as $id => $label): ?>
                <option value="<?= $id; ?>" <?php selected($id, $formId); ?>>
                    <?= $label; ?><?php if (!empty($id)) { printf(' (%s)', $id); } ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div id="glsrt-tags" class="flex items-center"></div>
</div>
