<div class="glsr-card postbox">
    <h3 class="glsr-card-heading">
        <button type="button" class="glsr-accordion-trigger" aria-expanded="false" aria-controls="faq-enable-optgroup">
            <span class="title">How do I enable the &lt;optgroup&gt; tag in SELECT fields?</span>
            <span class="badge code">Review Forms</span>
            <span class="icon"></span>
        </button>
    </h3>
    <div id="faq-enable-optgroup" class="inside">
        <div class="glsr-notice-inline components-notice is-warning">
            <p class="components-notice__content">If you are not using the <a href="<?= glsr_admin_url('addons'); ?>">Review Forms</a> add-on, this question will probably not apply to you.</p>
        </div>
        <p>The <code>&lt;optgroup&gt;</code> tag is used to group items in a SELECT field. If you have parent/child categories, you may wish to group them in the "Review: Categories" field dropdown.</p>
        <p>To enable the optgroup tag in SELECT elements, use the following filter hook in your theme's <code>functions.php</code> file:</p>
        <pre><code class="language-php">add_filter('site-reviews/builder/enable/optgroup', '__return_true');</code></pre>
        <p><span class="required">Important:</span> If you do this to display grouped categories, the parent categories will not be selectable as they are used as the optgroup labels!</p>
    </div>
</div>
