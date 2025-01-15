<?php

namespace GeminiLabs\SiteReviews\Addon\Images;

use GeminiLabs\SiteReviews\Addon\Images\Defaults\ImageDefaults;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Request;

class Uploader
{
    public function attachImages(array $images, int $reviewId): void
    {
        $this->setUploadDirectory(glsr()->id);
        $order = 0;
        add_filter('intermediate_image_sizes', [$this, 'filterImageSizes']);
        foreach ($images as $index => $image) {
            $image = Arr::consolidate($image);
            $image = glsr(ImageDefaults::class)->merge($image);
            $attachmentId = $image['id'];
            if (empty($attachmentId)) {
                $attachmentId = $this->sideload($image, $reviewId);
                if (is_wp_error($attachmentId)) {
                    glsr_log()
                        ->error('['.Application::ID.'] '.$attachmentId->get_error_message())
                        ->debug(['image' => $image, 'review_post_id' => $reviewId]);
                    continue;
                }
            } elseif ('attachment' !== get_post_type($attachmentId)) {
                continue;
            }
            $attachment = get_post($attachmentId);
            $attachment->menu_order = $order;
            $attachment->post_excerpt = $image['caption'];
            $attachment->post_status = 'inherit';
            if (empty($image['id'])) {
                glsr(Application::ID)->action('uploaded', $attachment);
            }
            wp_update_post([
                'ID' => $attachment->ID,
                'menu_order' => $attachment->menu_order,
                'post_excerpt' => $attachment->post_excerpt,
                'post_status' => $attachment->post_status,
            ]);
            ++$order;
        }
        remove_filter('intermediate_image_sizes', [$this, 'filterImageSizes']);
    }

    public function attachRemoteImages(array $imageUrls, int $reviewId): int
    {
        $this->setUploadDirectory(glsr()->id);
        $title = sprintf(esc_attr_x('%s Image', 'image title', 'site-reviews-images'), glsr()->name);
        $urls = sprintf("'%s'", implode("','", $imageUrls));
        $sql = glsr(Query::class)->sql("
            SELECT p.ID, pm.meta_value AS url
            FROM table|posts AS p
            INNER JOIN table|postmeta AS pm ON (pm.post_id = p.ID)
            WHERE 1=1
            AND p.post_type = 'attachment'
            AND pm.meta_key = '_source_url'
            AND pm.meta_value IN ({$urls})
            AND p.guid LIKE '%/site-reviews/%'
        ");
        $attachments = glsr(Database::class)->dbGetResults($sql);
        $attachments = wp_list_pluck($attachments, 'ID', 'url');
        add_filter('intermediate_image_sizes', [$this, 'filterImageSizes']);
        $num = 0;
        foreach ($imageUrls as $url) {
            if (array_key_exists($url, $attachments)) {
                $attachmentId = $attachments[$url];
            } else {
                $attachmentId = media_sideload_image($url, $reviewId, $title, 'id');
            }
            if (is_wp_error($attachmentId)) {
                $message = $attachmentId->get_error_message();
                glsr_log()->error("$message [$url]");
                continue;
            }
            wp_update_post([
                'ID' => $attachmentId,
                'menu_order' => $num,
                'post_parent' => $reviewId,
                'post_status' => 'inherit',
            ]);
            ++$num;
        }
        remove_filter('intermediate_image_sizes', [$this, 'filterImageSizes']);
        return $num;
    }

    /**
     * @filter intermediate_image_sizes
     */
    public function filterImageSizes(array $sizes): array
    {
        $restrictTo = glsr(Application::ID)->filterArray('intermediate_image_sizes', [
            'large',
            'medium',
            'medium_large',
            'thumbnail',
        ]);
        foreach ($sizes as $index => $size) {
            if (!in_array($size, $restrictTo)) {
                unset($sizes[$index]);
            }
        }
        return $sizes;
    }

    /**
     * @return array|\WP_Error|void
     */
    public function handle(Request $request)
    {
        $method = Helper::buildMethodName('handle', $request->method);
        if (!method_exists($this, $method)) {
            glsr_log()->error('['.Application::ID.'] Invalid or missing method value in request')->info($request->toArray());
            return new \WP_Error();
        }
        return call_user_func([$this, $method], $request);
    }

    /**
     * Delete the attachment from WordPress.
     *
     * @return array|void
     */
    public function handleDelete(Request $request)
    {
        $attachment = get_post($request->id);
        if ('attachment' !== $attachment->post_type || empty($attachment->post_parent)) {
            return;
        }
        $parent = get_post($attachment->post_parent);
        if ($parent->post_type !== glsr()->post_type) {
            return;
        }
        wp_delete_attachment($attachment->ID);
        return [
            'id' => $attachment->ID,
        ];
    }

    /**
     * Delete the temporary image.
     *
     * @return array|void
     */
    public function handlePurge(Request $request)
    {
        if (file_exists($request->file)) {
            wp_delete_file($request->file);
            return [
                'file' => $request->file,
            ];
        }
    }

    /**
     * Upload images to the temp directory.
     *
     * @return array|\WP_Error
     */
    public function handleUpload()
    {
        $this->setUploadDirectory(glsr()->id.'/temp', true);
        $upload = wp_handle_upload($_FILES['file'], [
            'mimes' => $this->getValidMimeTypes(),
            'test_form' => false,
        ]);
        return !empty($upload['error'])
            ? new \WP_Error(Application::ID, $upload['error'], $upload)
            : $upload;
    }

    /**
     * @param string $path
     * @param bool   $forceFlatDirectory
     *
     * @return array
     */
    public function setUpload(array $upload, $path, $forceFlatDirectory = false)
    {
        if (!$forceFlatDirectory && get_option('uploads_use_yearmonth_folders')) {
            $time = current_time('mysql');
            $y = substr($time, 0, 4);
            $m = substr($time, 5, 2);
            $upload['subdir'] = "/$y/$m";
        }
        $upload['subdir'] = '/'.$path.$upload['subdir'];
        $upload['path'] = $upload['basedir'].$upload['subdir'];
        $upload['url'] = $upload['baseurl'].$upload['subdir'];
        wp_mkdir_p($upload['path']);
        return $upload;
    }

    /**
     * @param string $path
     * @param bool   $forceFlatDirectory
     *
     * @return void
     */
    public function setUploadDirectory($path, $forceFlatDirectory = false)
    {
        add_filter('upload_dir', function ($upload) use ($path, $forceFlatDirectory) {
            return $this->setUpload($upload, $path, $forceFlatDirectory);
        });
    }

    /**
     * @return int|\WP_Error
     */
    public function sideload(array $image, int $reviewId)
    {
        $image = glsr(ImageDefaults::class)->merge($image);
        $title = sprintf(esc_attr_x('%s Image', 'image title', 'site-reviews-images'), glsr()->name);
        $extension = pathinfo($image['file'], PATHINFO_EXTENSION);
        $filename = wp_basename($image['file']);
        $filename = glsr(Application::ID)->filterString('filename', $filename, $extension);
        $file = [
            'name' => $filename,
            'tmp_name' => $image['file'],
        ];
        $attachmentId = media_handle_sideload($file, $reviewId, $title);
        if (!is_wp_error($attachmentId)) {
            $this->handlePurge(new Request($image));
        }
        return $attachmentId;
    }

    protected function getUploadPath($path): string
    {
        $dir = trailingslashit(wp_upload_dir()['basedir']).trailingslashit(glsr()->id).$path;
        wp_mkdir_p($dir);
        return trailingslashit($dir);
    }

    protected function getValidMimeTypes(): array
    {
        return [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
        ];
    }
}
