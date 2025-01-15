<?php defined('ABSPATH') || exit;

$filters = array_keys(glsr('site-reviews-filters')->config('forms/filters-form'));
sort($filters);
$filters = implode(',', $filters);

?>
<p class="glsr-heading">hide</p>
<p>Include the "hide" option to hide any specific fields you don't want to show. If all fields are hidden, the shortcode will not be displayed.</p>
<div class="shortcode-example">
    <pre><code class="language-shortcode">[site_reviews_filters hide="<?= $filters; ?>"]</code></pre>
</div>
