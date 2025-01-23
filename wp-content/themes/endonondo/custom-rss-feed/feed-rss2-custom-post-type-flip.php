<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed for the Top 10 Popular posts.
 *
 */

$parts = explode('-', $feed);
$endparts = end($parts);

$op = 'rss_description';

if ($endparts != 'feed') {
    $op = $endparts . '_description';
}

$pt = ['informational_posts'];

if($endparts == 'exercise') {
    $pt = ['informational_posts', 'exercise'];
}

$args = array(
    'post_type' => $pt,
    'posts_per_page' => 20,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
);

query_posts($args); // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts

header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
$more = 1; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

echo '<?xml version="1.0" encoding="' . esc_attr(get_option('blog_charset')) . '"?' . '>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @since 4.0.0
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action('rss_tag_pre', 'rss2');
?>
<rss version="2.0" <?php
/**
 * Fires at the end of the RSS root to add namespaces.
 *v
 * @since 2.0.0
 */
do_action('rss2_ns');
?>>

    <channel>
        <title>Endomondo</title>
        <link><?= self_link(); ?></link>
        <description><?= get_field($op, 'option') ?></description>
        <language><?php bloginfo_rss('language'); ?></language>
        <?php
        /**
         * Fires at the end of the RSS2 Feed Header.
         *
         * @since 2.0.0
         */

        while (have_posts()):
            the_post();
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            if (!$image_url) {
                $image_url = get_field('fimg_default', 'option');
            }
            ?>
            <item>
                <title><![CDATA[<?php echo html_entity_decode(get_the_title()); ?>]]></title>
                <link><?php the_permalink_rss(); ?></link>
                <guid isPermaLink="false"><?php the_guid(); ?></guid>
                <pubDate>
                    <?php
                    $post_time_utc = get_post_time('Y-m-d H:i:s', true);
                    $date = new DateTime($post_time_utc, new DateTimeZone('UTC'));
                    $date->modify('-8 hours');
                    echo $date->format('D, d M Y H:i:s -0800');
                    ?>
                </pubDate>
                <dc:creator><![CDATA[<?php the_author(); ?>]]></dc:creator>
                <?php the_category_rss('rss2'); ?>
                <description>
                    <![CDATA[
                <?php
                $yoast_description = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);
                if (!empty($yoast_description)) {
                    echo $yoast_description;
                } else {
                    the_excerpt_rss();
                }
                ?>
                ]]>
                </description>
                <?php if ($image_url): ?>
                    <?php $image_type = 'image/' . pathinfo(parse_url($image_url, PHP_URL_PATH), PATHINFO_EXTENSION); ?>
                    <enclosure url="<?php echo esc_url($image_url); ?>" type="<?php echo esc_attr($image_type); ?>" />
                <?php endif; ?>
                <?php do_action('rss2_item'); ?>
            </item>
        <?php endwhile; ?>

    </channel>
</rss>