<?php

namespace GeminiLabs\SiteReviews\Addon\Images;

use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;

class Attachment
{
    /**
     * @param int|int[] $attachmentIds
     * @param int $parentId
     * @return bool
     */
    public function attach($attachmentIds, $parentId)
    {
        return $this->process($attachmentIds, $parentId, 'attach');
    }

    /**
     * @param int|int[] $attachmentIds
     * @param int $parentId
     * @return bool
     */
    public function detach($attachmentIds, $parentId)
    {
        return $this->process($attachmentIds, $parentId, 'detach');
    }

    /**
     * @param int[] $attachmentIds
     * @return void
     */
    public function normalize($attachmentIds)
    {
        $attachmentIds = Arr::uniqueInt(Cast::toArray($attachmentIds));
        foreach ($attachmentIds as $index => $attachmentId) {
            if ($attachment = get_post($attachmentId)) {
                $attachment->menu_order = Cast::toInt($index);
                $attachment->post_name = sprintf('%s-%s', $attachment->post_title, $attachment->ID);
                $attachment->post_status = 'inherit';
                glsr(Application::ID)->action('uploaded', $attachment);
                wp_update_post([
                    'ID' => $attachment->ID,
                    'menu_order' => $attachment->menu_order,
                    'post_name' => $attachment->post_name,
                    'post_status' => $attachment->post_status,
                ]);
            }
        }
    }

    /**
     * @param int|int[] $attachmentIds
     * @param int $parentId
     * @param string $action
     * @return bool
     */
    protected function process($attachmentIds, $parentId, $action)
    {
        global $wpdb;
        $attachmentIds = Arr::uniqueInt(Cast::toArray($attachmentIds));
        $parentId = Cast::toInt($parentId);
        if (empty($attachmentIds) || empty($parentId)) {
            return false;
        }
        $ids = implode(',', $attachmentIds);
        $postParent = Helper::ifTrue('attach' === $action, $parentId, 0);
        $menuOrder = Helper::ifTrue('detach' === $action, ', menu_order = 0', ''); // reset the menu order if detatching
        $result = $wpdb->query($wpdb->prepare("
            UPDATE {$wpdb->posts}
            SET post_parent = %d {$menuOrder}
            WHERE post_type = 'attachment' AND ID IN ({$ids})
        ", $postParent));
        foreach ($attachmentIds as $attachmentId) {
            do_action('wp_media_attach_action', $action, $attachmentId, $parentId);
            clean_attachment_cache($attachmentId);
        }
        return true;
    }
}
