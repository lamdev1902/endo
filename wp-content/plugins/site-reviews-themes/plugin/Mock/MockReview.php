<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Template;
use GeminiLabs\SiteReviews\Defaults\SiteReviewsDefaults;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Review;

class MockReview extends \ArrayObject
{
    /**
     * @var \GeminiLabs\SiteReviews\Arguments
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

    public function __construct(Review $review, array $args = [])
    {
        $this->args = glsr()->args($args);
        $this->review = $review;
    }

    /**
     * @return void
     */
    public function build()
    {
        /**
         * checkbox [::Multi]
         * file (images)
         * select (radio|toggle|terms)
         * rating [::Rating]
         * text (title|name|number)
         * textarea (content|response)
         * url (email|tel) [::Url]
         * assigned_posts
         * assigned_terms
         * assigned_users
         * assigned_links
         */
        $tags = [];
        if (glsr()->addon('site-reviews-forms') && !empty($this->args->form)) {
            $fields = glsr('Addon\Forms\FormFields')->customFields($this->args->form);
            $fields = array_filter($fields, function ($field) {
                return !empty(Arr::get($field, 'tag'));
            });


        }


        $fields = glsr(FormFields::class)->customFields($formId);

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
    public function toArray()
    {
    }
}
