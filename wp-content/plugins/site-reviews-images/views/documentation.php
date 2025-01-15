<?php defined('WPINC') || die;

glsr()->render('views/partials/youtube', [
    'youtube_bg' => 'https://i.ytimg.com/vi/xEDbkyfRxNw/maxresdefault.jpg',
    'youtube_id' => 'xEDbkyfRxNw',
]);

?>

<h3>How do I change the filename of uploaded images?</h3>
<p>
    <pre><code class="language-php">/**
 * Rename image files before they are saved
 * Paste this in your active theme's functions.php file.
 * @param string $filename
 * @param string $extension
 * @return array
 */
add_filter('site-reviews-images/filename', function ($filename, $extension) {
    // You can change the filename like this
    $filename = sprintf('my_image.%s', $extension);
    return $filename;
}, 10, 2);</code></pre>
</p>

<h3>How do I change the post_name of uploaded images?</h3>
<p>The attachment is saved after this hook is run, so there is no need to save the attachment after modifying it.</p>
<p>
    <pre><code class="language-php">/**
 * Modify the uploaded attachment
 * Paste this in your active theme's functions.php file.
 * @param \WP_Post $attachment
 * @return void
 */
add_action('site-reviews-images/uploaded', function (\WP_Post $attachment) {
    // You can change the post_name like this
    $attachment->post_name = sprintf('my-image-%s', $attachment->ID);
});</code></pre>
</p>
