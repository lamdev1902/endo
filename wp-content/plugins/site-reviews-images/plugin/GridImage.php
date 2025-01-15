<?php

namespace GeminiLabs\SiteReviews\Addon\Images;

use GeminiLabs\SiteReviews\Addon\Images\Defaults\GridImageDefaults;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Review;

/**
 * @property int $ID
 * @property int $index
 * @property int $review_id
 */
class GridImage extends Arguments
{
    protected $_large;
    protected $_medium;
    protected $_thumbnail;

    /**
     * @param array|object $values
     */
    public function __construct($values)
    {
        parent::__construct(
            glsr(GridImageDefaults::class)->restrict(Arr::consolidate($values))
        );
    }

    /**
     * @return string
     */
    public function caption()
    {
        return Cast::toString(wp_get_attachment_caption($this->ID));
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (wp_attachment_is_image($this->ID)) {
            return Review::isReview(get_post($this->ID)->post_parent);
        }
        return false;
    }

    /**
     * @return Arguments
     */
    public function medium()
    {
        if (empty($this->_medium)) {
            $this->_medium = $this->size('medium');
        }
        return $this->_medium;
    }

    /**
     * @return Arguments
     */
    public function large()
    {
        if (empty($this->_large)) {
            $this->_large = $this->size('large');
        }
        return $this->_large;
    }

    /**
     * @param string $size
     * @return Arguments
     */
    public function size($size)
    {
        $image = wp_get_attachment_image_src($this->ID, $size);
        return glsr()->args([
            'height' => Cast::toInt(Arr::get($image, 2)),
            'sizes' => $this->sizes($size),
            'src' => Arr::get($image, 0),
            'srcset' => $this->srcset($size),
            'width' => Cast::toInt(Arr::get($image, 1)),
        ]);
    }

    /**
     * @param string $size
     * @return string
     */
    public function sizes($size)
    {
        return wp_get_attachment_image_sizes($this->ID, $size);
    }

    /**
     * @param string $size
     * @return string
     */
    public function srcset($size)
    {
        return wp_get_attachment_image_srcset($this->ID, $size);
    }

    /**
     * @return string
     */
    public function tag($size = 'large')
    {
        $size = $this->size($size);
        return glsr(Builder::class)->img([
            'alt' => $this->caption(),
            'data-id' => $this->ID,
            'data-index' => $this->index,
            'data-review_id' => $this->review_id,
            'height' => $size->height, // @phpstan-ignore-line
            'src' => $size->src, // @phpstan-ignore-line
            'width' => $size->width, // @phpstan-ignore-line
        ]);
    }

    /**
     * @return Arguments
     */
    public function thumbnail()
    {
        if (empty($this->_thumbnail)) {
            $this->_thumbnail = $this->size('thumbnail');
        }
        return $this->_thumbnail;
    }

    /**
     * @param array $sizes Optional parameter that can be used to change the output
     */
    public function toArray(array $sizes = []): array
    {
        $image = $this->getArrayCopy();
        $image['caption'] = $this->caption();
        if (!Arr::isIndexedAndFlat($sizes)) {
            $image['large'] = $this->large()->toArray();
            $image['medium'] = $this->medium()->toArray();
            $image['thumbnail'] = $this->thumbnail()->toArray();
            return $image;
        }
        if (1 === count($sizes)) {
            return wp_parse_args($image, $this->size($sizes[0])->toArray());
        }
        foreach ($sizes as $size) {
            $image[$size] = $this->size($size)->toArray();
        }
        return $image;
    }
}
