<?php defined('WPINC') || die; ?>

<h3>How do I disable alphabetisation of dropdown items when using choices.js?</h3>
<p>
    <pre><code class="language-php">/**
 * Disable alphabetisation of choice.js dropdowns.
 * Paste this in your active theme's functions.php file.
 * @return array
 */
add_filter('site-reviews/enqueue/public/localize', function (array $variables) {
    if (!empty($variables['addons']['site-reviews-forms']['choicesjs'])) {
        $variables['addons']['site-reviews-forms']['choicesjs']['shouldSort'] = false;
    }
    return $variables;
});</code></pre>
</p>

<h3>How do I display reviews that were submitted with a specific form?</h3>
<p>Site Reviews allows you to use the "assigned_posts" option with multiple IDs, so you can assign reviews to both the form and the current page.</p>
<p class="glsr-heading">Use a custom form with a Post ID of "13", and assign reviews to both the form and the current page:</p>
<div class="shortcode-example">
    <pre><code class="language-shortcode">[site_reviews_form form="13" assigned_posts="13,post_id"]</code></pre>
</div>
<p class="glsr-heading">Display reviews using the review template of the custom form, assigned to both the form and the current page:</p>
<div class="shortcode-example">
    <pre><code class="language-shortcode">[site_reviews form="13" assigned_posts="13,post_id"]</code></pre>
</div>
<p>
    <span class="required">Important:</span>
    <?= sprintf(_x('If you assigning reviews to multiple Post IDs, then you probably also want to make sure that the %s setting is set to "Strict Assignment".', 'admin-text', 'site-reviews-forms'),
        sprintf('<a href="%s">%s</a>', glsr_admin_url('settings', 'reviews'), 'Review Assignment')
    ); ?>
</p>

<h3>How do I display the rating label before the stars in reviews?</h3>
<p>Use the following custom CSS in your theme:</p>
<p>
    <pre><code class="language-css">.glsr[data-form] .glsr-tag-label + .glsr-stars {
    order: revert !important;
}</code></pre>
</p>

<h3>Why is the "hide" shortcode option not working?</h3>
<p>The field settings in custom forms will override the hide option in the shortcodes. Instead of using the hide option, change the field options in the form instead.</p>
