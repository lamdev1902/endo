<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Template;
use GeminiLabs\SiteReviews\Defaults\SiteReviewsDefaults;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Review;

class MockReviewOld extends \ArrayObject
{
    /**
     * @var array
     */
    public $args;

    /**
     * @var string
     */
    public $html;

    /**
     * @var Review
     */
    public $review;

    /**
     * @var string
     */
    public $template;

    public function __construct($template, array $args = [])
    {
        $this->args = glsr(SiteReviewsDefaults::class)->merge($args);
        $this->review = new Review($this->dummyData());
        $this->review->set('assigned_links', '');
        $this->review->set('review_id', 0);
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->build()->html;
    }

    /**
     * @return self
     */
    public function build()
    {
        $args = $this->args;
        $tags = [];
        foreach ($this->review as $tag => $value) {
            $className = Helper::buildClassName($tag.'-tag', __NAMESPACE__.'\Tags');
            $field = class_exists($className)
                ? glsr($className, compact('tag', 'args'))->handleFor('review', $value, $this->review)
                : Cast::toString($value, false);
            $tags[$tag] = $field;
        }
        $this->html = glsr(Template::class)->interpolate($this->template, 'mock-path', [
            'context' => $tags,
        ]);
        return $this;
    }

    /**
     * @return array
     */
    public function dummyData(array $args = [])
    {
        return wp_parse_args($args, [
            'author' => 'Jane Doe',
            'date' => wp_date('Y-m-d H:i:s', strtotime('-3 Months')),
            'title' => 'Review Title',
            'rating' => 5,
            'content' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.',
        ]);
    }
}
