<?php
/**
 * RSS2 Feed Template for displaying RSS2 Posts feed for the Top 10 Popular posts.
 *
 */
$args = array(
    'post_type' => ['informational_posts', 'exercise'],
    'posts_per_page' => 10,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
);
query_posts($args); // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts
header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
$more = 1;
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
<rss version="2.0" xmlns:nb="https://www.newsbreak.com/" xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/" <?php
    /**
     * Fires at the end of the RSS root to add namespaces.
     *v
     * @since 2.0.0
     */
    do_action('rss2_ns');
    ?>>

    <channel>
        <title>Endomondo</title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <link><?= home_url(); ?></link>
        <description><?php bloginfo_rss('description'); ?></description>
        <lastBuildDate>
            <?php
            $build_date = get_feed_build_date('Y-m-d H:i:s');

            $date = new DateTime($build_date, new DateTimeZone('UTC'));
            $date->modify('-8 hours'); 
            echo $date->format('l, j M Y H:i:s -0800');
            ?>

        </lastBuildDate>
        <language><?php bloginfo_rss('language'); ?></language>
        <sy:updatePeriod>
            <?php
            $duration = 'hourly';

            /**
             * Filters how often to update the RSS feed.
             *
             * @since 2.1.0
             *
             * @param string $duration The update period. Accepts 'hourly', 'daily', 'weekly', 'monthly',
             *                         'yearly'. Default 'hourly'.
             */
            echo apply_filters('rss_update_period', $duration); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        </sy:updatePeriod>
        <sy:updateFrequency>
            <?php
            $frequency = '1';

            /**
             * Filters the RSS update frequency.
             *
             * @since 2.1.0
             *
             * @param string $frequency An integer passed as a string representing the frequency
             *                          of RSS updates within the update period. Default '1'.
             */
            echo apply_filters('rss_update_frequency', $frequency); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        </sy:updateFrequency>
        <?php
        /**
         * Fires at the end of the RSS2 Feed Header.
         *
         * @since 2.0.0
         */
        do_action('rss2_head');

        while (have_posts()):
            the_post();
            $exerciseId = get_post_meta($post->ID, 'exercise_name', true);
            $title = get_post_meta($post->ID, '_feed_title', true);
            ?>
            <item>
                <title><![CDATA[<?php echo html_entity_decode($title ?: get_the_title()); ?>]]></title>
                <link><?php the_permalink_rss(); ?></link>
                <guid isPermaLink="false"><?php the_guid(); ?></guid>
                <pubDate>
                    <?php
                    $post_time_utc = get_post_time('Y-m-d H:i:s', true);
                    $date = new DateTime($post_time_utc, new DateTimeZone('UTC'));
                    $date->modify('-8 hours'); 
                    echo $date->format('l, j M Y H:i:s -0800');
                    ?>
                </pubDate>
                <dc:creator><![CDATA[<?php the_author(); ?>]]></dc:creator>
                <?php the_category_rss('rss2'); ?>
                <description>
                    <![CDATA[
                <?php
                $yoast_description = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);

                if (!empty($yoast_description)) {
                    $yoast_description = str_replace('%%currentyear%%', date('Y'), $yoast_description);
                    echo do_shortcode($yoast_description);
                } else {
                    the_excerpt_rss();
                }
                ?>
                ]]>
                </description>
                <content:encoded>
                    <![CDATA[
                    <?php
                    ob_start();
                    ?>
                    <figure>
                            <?php
                            $image_featured = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                            if ($image_featured) { ?>
                                <img src="<?php echo esc_url($image_featured); ?>" alt="">
                            <?php } else { ?>
                                <img src="<?php echo esc_url(get_field('fimg_default', 'option')); ?>" alt="">
                            <?php }
                            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
                            $caption = wp_get_attachment_caption($post_thumbnail_id);
                            if (!empty($caption)) { ?>
                                <figcaption><?php echo wp_kses_post($caption); ?></figcaption>
                            <?php } ?>
                        </figure>
                    <?php
                    if ($exerciseId) {
                        get_template_part('template-parts/content', 'exercise');
                    }
                    $the_content = get_the_content();
                    $the_content = preg_replace_callback(
                        '/<div class="wp-block-embed__wrapper">\s*https:\/\/vimeo\.com\/(\d+)(?:\?[^\s<]*)?\s*<\/div>/is',
                        function ($matches) {
                            $video_id = $matches[1];
                            return '<iframe class="nb-video"
                                        src="https://player.vimeo.com/video/' . esc_attr($video_id) . '?title=1&byline=1&portrait=1&dnt=false" 
                                        width="550" 
                                        height="281" 
                                        frameborder="0" 
                                        allow="autoplay; fullscreen; picture-in-picture" 
                                        allowfullscreen>
                                    </iframe>';
                        },
                        $the_content
                    );
                    $the_content = preg_replace_callback(
                        '/<div class="wp-block-embed__wrapper">\s*(?:https:\/\/(?:www\.)?youtube\.com\/watch\?v=([\w\-]+)|https:\/\/youtu\.be\/([\w\-]+))(?:\?[^\s<]*)?\s*<\/div>/is',
                        function ($matches) {
                            $video_id = !empty($matches[1]) ? $matches[1] : $matches[2];
                            return '<iframe
                                        class="nb-video"
                                        width="550" 
                                        height="281" 
                                        src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" 
                                        title="YouTube video player" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                    </iframe>';
                        },
                        $the_content
                    );
                    $the_content = preg_replace('/<table\b[^>]*>.*?<\/table>/is', '', $the_content);
                    $the_content = preg_replace('/<h4[^>]*>Optimal Sets And Reps<\/h4>/i', '', $the_content);
                    $the_content = preg_replace('/<h4[^>]*><strong>Optimal Sets And Reps<\/strong><\/h4>/i', '', $the_content);
                    // $the_content = preg_replace(
                    //  '/<div class="wp-block-group medicine-table">.*?<\/div>/is',
                    //  '',
                    //  $the_content
                    // );
                    // $the_content = preg_replace(
                    //  '/<div class="wp-block-group medicine-table no-scroll">.*?<\/div>/is',
                    //  '', 
                    //  $the_content
                    // );
                    // $the_content = preg_replace(
                    //  '/<div class="wp-block-group medicine-table scroll">.*?<\/div>/is',
                    //  '', 
                    //  $the_content
                    // );
                    // $the_content = preg_replace(
                    //  '/<figure class="wp-block-table table-default">.*?<\/figure>/is',
                    //  '', 
                    //  $the_content
                    // );
                    // $the_content = preg_replace(
                    //  '/<figure class="wp-block-table table-custom">.*?<\/figure>/is',
                    //  '', 
                    //  $the_content
                    // );
                
                    $the_content = preg_replace_callback(
                        '/<figure[^>]*>(?:[^<]*<(?!\/?figure)[^>]*>)*?<table.*?>.*?<\/table>(?:[^<]*<(?!\/?figure)[^>]*>)*?<\/figure>/is',
                        function ($matches) {
                            return '';
                        },
                        $the_content
                    );

                    $the_content = preg_replace(
                        '/<div class="wp-block-group medicine-table">.*?<\/div>/is',
                        '',
                        $the_content
                    );
                    $the_content = preg_replace(
                        '/<div class="wp-block-group medicine-table no-scroll">.*?<\/div>/is',
                        '',
                        $the_content
                    );
                    $the_content = preg_replace(
                        '/<div class="wp-block-group medicine-table scroll">.*?<\/div>/is',
                        '',
                        $the_content
                    );
                    $the_content = preg_replace(
                        '/<figure[^>]*\bclass="[^"]*\bwp-block-table\b[^"]*"[^>]*>.*?<\/figure>/is',
                        '',
                        $the_content
                    );

                    $the_content = preg_replace(
                        '/\[anatomir\s+value="[^"]*"\]/i',
                        '',
                        $the_content
                    );

                    // $the_content = preg_replace('/\s*style="[^"]*"/i', '', $the_content);
                
                    $allowed_tags = "<figure><iframe><img><a><b><strong><i><li><left><center><right><del><strike><ol><ul><u><sup><pre><code><sub><hr><h1><h2><h3><h4><h5><h6><big><small><font><p><br><span><div><video><audio><dd><dl>";
                    $the_content = htmlspecialchars_decode($the_content);
                    $the_content = strip_tags($the_content, $allowed_tags);
                    $the_content = preg_replace("/\r?\n/", "", $the_content);
                    $the_content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $the_content);
                    $the_content = preg_replace('/\s*(decoding|loading|sizes)="[^"]*"/', '', $the_content);
                    $permalink = get_permalink();
                    $the_content = preg_replace_callback(
                        '/<a\s+([^>]*?)href=["\'](.*?)["\']([^>]*?)>/i',
                        function ($matches) {
                            $href = trim($matches[2]);
                            if ($href === '' || $href === '#') {
                                return '<a ' . $matches[1] . $matches[3] . '>';
                            } elseif (strpos($href, 'http') === false) {
                                $absolute_url = home_url('/') . ltrim($href, '/');
                                return '<a ' . $matches[1] . 'href="' . esc_url($absolute_url) . '"' . $matches[3] . '>';
                            }
                            return $matches[0];
                        },
                        $the_content
                    );
                    echo $the_content;
                    $content = ob_get_clean();
                    echo $content;
                    ?>
                    ]]>
                </content:encoded>
                <?php if (get_comments_number() || comments_open()): ?>
                    <wfw:commentRss><?php echo esc_url(get_post_comments_feed_link(get_the_ID(), 'rss2')); ?>
                    </wfw:commentRss>
                    <slash:comments><?php echo get_comments_number(); ?></slash:comments>
                <?php endif; ?>

                <?php rss_enclosure(); ?>
                <?php do_action('rss2_item'); ?>
            </item>
        <?php endwhile; ?>

    </channel>
</rss>