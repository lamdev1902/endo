<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Integrations\Elementor;

use Elementor\Controls_Manager;
use GeminiLabs\SiteReviews\Addon\Images\Shortcodes\SiteReviewsImagesShortcode;
use GeminiLabs\SiteReviews\Integrations\Elementor\ElementorWidget;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Rating;

class ElementorImagesWidget extends ElementorWidget
{
    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-image';
    }

    /**
     * @return array
     */
    public function get_script_depends()
    {
        return [glsr()->id.'/splide'];
    }

    /**
     * @return string
     */
    public function get_shortcode()
    {
        return SiteReviewsImagesShortcode::class;
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return _x('Review Images', 'admin-text', 'site-reviews-images');
    }

    protected function settings_basic(): array
    {
        $options = [
            'assigned_posts' => [
                'default' => '',
                'label' => _x('Limit Reviews to an Assigned Page', 'admin-text', 'site-reviews-images'),
                'label_block' => true,
                'options' => $this->assigned_posts_options(),
                'type' => Controls_Manager::SELECT2,
            ],
            'assigned_posts_custom' => [
                'condition' => ['assigned_posts' => 'custom'],
                'description' => _x('Separate values with a comma.', 'admin-text', 'site-reviews-images'),
                'label_block' => true,
                'placeholder' => _x('Enter the Post IDs', 'admin-text', 'site-reviews-images'),
                'show_label' => false,
                'type' => Controls_Manager::TEXT,
            ],
            'assigned_terms' => [
                'default' => '',
                'label' => _x('Limit Reviews to an Assigned Category', 'admin-text', 'site-reviews-images'),
                'label_block' => true,
                'multiple' => true,
                'options' => $this->assigned_terms_options(),
                'type' => Controls_Manager::SELECT2,
            ],
            'assigned_users' => [
                'default' => '',
                'label' => _x('Limit Reviews to an Assigned User', 'admin-text', 'site-reviews-images'),
                'label_block' => true,
                'options' => $this->assigned_users_options(),
                'type' => Controls_Manager::SELECT2,
            ],
            'assigned_users_custom' => [
                'condition' => ['assigned_users' => 'custom'],
                'description' => _x('Separate values with a comma.', 'admin-text', 'site-reviews-images'),
                'label_block' => true,
                'placeholder' => _x('Enter the User IDs', 'admin-text', 'site-reviews-images'),
                'show_label' => false,
                'type' => Controls_Manager::TEXT,
            ],
            'terms' => [
                'default' => '',
                'label' => _x('Limit Reviews to terms', 'admin-text', 'site-reviews-images'),
                'label_block' => true,
                'options' => [
                    'true' => _x('Terms were accepted', 'admin-text', 'site-reviews-images'),
                    'false' => _x('Terms were not accepted', 'admin-text', 'site-reviews-images'),
                ],
                'type' => Controls_Manager::SELECT2,
            ],
            'type' => $this->get_review_types(),
            'display' => [
                'default' => 8,
                'label' => _x('Maximum number of images to display', 'admin-text', 'site-reviews-images'),
                'max' => 50,
                'min' => 1,
                'type' => Controls_Manager::NUMBER,
            ],
            'rating' => [
                'default' => 0,
                'label' => _x('Minimum Rating', 'admin-text', 'site-reviews-images'),
                'max' => Cast::toInt(glsr()->constant('MAX_RATING', Rating::class)),
                'min' => Cast::toInt(glsr()->constant('MIN_RATING', Rating::class)),
                'separator' => 'before',
                'type' => Controls_Manager::NUMBER,
            ],
        ];
        $hideOptions = $this->get_shortcode_instance()->getHideOptions();
        foreach ($hideOptions as $key => $label) {
            $separator = $key === key(array_slice($hideOptions, 0, 1)) ? 'before' : 'default';
            $options['hide-'.$key] = [
                'label' => $label,
                'separator' => $separator,
                'return_value' => '1',
                'type' => Controls_Manager::SWITCHER,
            ];
        }
        return $options;
    }
}
