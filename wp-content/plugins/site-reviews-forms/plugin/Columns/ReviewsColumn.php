<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Columns;

class ReviewsColumn extends Column
{
    public function build(string $value = ''): string
    {
        global $wpdb;
        $reviewCount = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(DISTINCT m.post_id) AS reviews
            FROM {$wpdb->postmeta} m
            INNER JOIN {$wpdb->posts} p ON (p.ID = m.post_id)
            WHERE p.post_type = '%s'
            AND p.post_status = 'publish'
            AND m.meta_key = '_custom_form'
            AND m.meta_value = '%s'
        ", glsr()->post_type, $this->postId));
        $url = admin_url('edit.php?post_type='.glsr()->post_type.'&form='.$this->postId);
        return sprintf('<a href="%s">%s</a>', $url, $reviewCount);
    }
}
