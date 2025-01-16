<?php

namespace GeminiLabs\SiteReviews\Addon\Authors\Forms;

use GeminiLabs\SiteReviews\Contracts\FieldContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Modules\Encryption;
use GeminiLabs\SiteReviews\Modules\Html\ReviewForm;
use GeminiLabs\SiteReviews\Review;

class UpdateReviewForm extends ReviewForm
{
    protected Review $review;

    public function __construct(Review $review, array $args = [])
    {
        $this->review = $review;
        $overrides = [
            'button_text' => __('Update review', 'site-reviews-authors'),
            'button_text_loading' => __('Updating, please wait...', 'site-reviews-authors'),
        ];
        $values = array_merge(
            $review->toArray(),
            $review->custom()->toArray(),
        );
        parent::__construct(wp_parse_args($overrides, $args), $values);
    }

    public function images(): array
    {
        $attachmentIds = Arr::consolidate($this->review->images);
        $images = [];
        foreach ($attachmentIds as $attachmentId) {
            $file = wp_get_original_image_path($attachmentId);
            if (!$file) {
                continue;
            }
            $size = wp_getimagesize($file);
            $url = wp_get_original_image_url($attachmentId);
            $images[] = [
                'accepted' => true,
                'caption' => wp_get_attachment_caption($attachmentId),
                'dataURL' => $url,
                'file' => $file,
                'height' => $size[0],
                'id' => $attachmentId,
                'lastModified' => '',
                'name' => wp_basename($file),
                'processing' => true,
                'size' => wp_filesize($file),
                'status' => 'success',
                'type' => $size['mime'],
                'width' => $size[0],
            ];
        }
        return $images;
    }

    /**
     * @return FieldContract[]
     */
    protected function fieldsHidden(): array
    {
        do_action('litespeed_nonce', 'update-review'); // @litespeedcache
        $config = [
            '_action' => 'update-review',
            '_nonce' => wp_create_nonce('update-review'),
            'excluded' => glsr(Encryption::class)->encrypt($this->args->cast('hide', 'string')),
            'form' => $this->args->form,
            'form_id' => $this->args->id,
            'review_id' => $this->review->ID,
            'theme' => $this->args->theme,
        ];
        $fields = [];
        foreach ($config as $name => $value) {
            $field = $this->field($name, [
                'type' => 'hidden',
                'value' => $value,
            ]);
            if ($field->isValid()) {
                $fields[$name] = $field;
            }
        }
        return $fields;
    }
}
