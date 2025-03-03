<?php
$source_content = get_field('source_content', $postid);
if (get_field('enable_source', 'option') == true && $source_content) {
    ?>
    <div class="sg-resources mr-bottom-20 pd-main">
        <h3>Resources</h3>
        <div class="intro">
            <?= get_field('source_intro', 'option'); ?>
        </div>
        <?= $source_content; ?>
    </div>
<?php } ?>