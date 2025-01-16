<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Controllers;

use GeminiLabs\SiteReviews\Addon\Images\Uploader;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Defaults\ImportResultDefaults;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;

class ImportController extends AbstractController
{
    /**
     * @filter site-reviews/import/reviews/attachments
     */
    public function filterAttachRemoteImages(array $result, int $limit, int $offset): array
    {
        $sql = glsr(Query::class)->sql("
            SELECT data
            FROM table|tmp
            WHERE type = 'attachment'
            LIMIT {$offset}, {$limit}
        ");
        $results = glsr(Database::class)->dbGetCol($sql);
        $imported = 0;
        $skipped = 0;
        foreach ($results as $index => $data) {
            $data = maybe_unserialize($data);
            $data = Arr::consolidate($data);
            foreach ($data as $reviewId => $images) {
                $request = new Request(compact('images'));
                $this->normalizeRequest($request);
                $total = count($request->images);
                if (!Review::isReview($reviewId)) {
                    $skipped += $total;
                    continue;
                }
                if (empty($request->images)) {
                    $skipped += $total;
                    continue;
                }
                $attached = glsr(Uploader::class)->attachRemoteImages($request->images, $reviewId);
                $skipped += max(0, $total - $attached);
                $imported += $attached;
            }
        }
        return glsr(ImportResultDefaults::class)->restrict([
            'imported' => $imported,
            'message' => _x('Imported %d images', 'admin-text', 'site-reviews-images'),
            'skipped' => $skipped,
        ]);
    }

    /**
     * @filter site-reviews/import/review/attachments
     */
    public function filterImportRemoteImages(int $imported, Request $request, Review $review): int
    {
        $images = $request->cast('images', 'array');
        if (empty($images)) {
            return 0;
        }
        $imported = glsr(Database::class)->insert('tmp', [
            'data' => maybe_serialize([$review->ID => $images]),
            'type' => 'attachment',
        ]);
        return Cast::toInt($imported);
    }

    /**
     * @action site-reviews/review/request
     */
    public function normalizeRequest(Request $request): void
    {
        if (!defined('WP_IMPORTING')) {
            return;
        }
        $images = $request->cast('images', 'string');
        $images = array_map('trim', explode('|', $images));
        foreach ($images as &$url) {
            $url = glsr(Sanitizer::class)->sanitizeUrl($url);
            if (empty($url)) {
                continue;
            }
            $filename = basename(current(explode('?', $url)));
            $filetype = wp_check_filetype($filename, [
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
            ]);
            if (empty(array_filter($filetype))) {
                glsr_log("Skipping unsupported remote image [$url]");
                $url = '';
            }
        }
        $images = array_values(Arr::unique($images));
        $request->set('images', $images);
    }
}
