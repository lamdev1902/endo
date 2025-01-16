<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Integrations\Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\Shortcodes\SiteReviewsFieldShortcode;
use GeminiLabs\SiteReviews\Integrations\Elementor\ElementorWidget;

class ElementorFieldSummaryWidget extends ElementorWidget
{
    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-glsr-field';
    }

    /**
     * @return string
     */
    public function get_shortcode()
    {
        return SiteReviewsFieldShortcode::class;
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return _x('Field Summary', 'admin-text', 'site-reviews-forms');
    }

    protected function get_control_sections(): array
    {
        $sections = parent::get_control_sections();
        $sections['style_label'] = [
            'controls' => $this->settings_style_label(),
            'label' => _x('Label', 'admin-text', 'site-reviews-forms'),
            'tab' => Controls_Manager::TAB_STYLE,
        ];
        $sections['style_labels'] = [
            'controls' => $this->settings_style_labels(),
            'label' => _x('Labels', 'admin-text', 'site-reviews-forms'),
            'tab' => Controls_Manager::TAB_STYLE,
        ];
        $sections['style_value'] = [
            'controls' => $this->settings_style_value(),
            'label' => _x('Value', 'admin-text', 'site-reviews-forms'),
            'tab' => Controls_Manager::TAB_STYLE,
        ];
        return $sections;
    }

    protected function settings_basic(): array
    {
        $options = [
            'form_notice' => [
                'condition' => [
                    'form' => '',
                ],
                'content' => esc_html_x('Select a review form that has a custom rating or range field.', 'admin-text', 'site-reviews-forms'),
                'type' => Controls_Manager::ALERT,
            ],
            'form' => [
                'default' => '',
                'label' => _x('Select the Custom Form', 'admin-text', 'site-reviews-forms'),
                'label_block' => true,
                'options' => glsr(Application::class)->posts(),
                'type' => Controls_Manager::SELECT2,
                'event' => 'renderMeee',
            ],
            'field' => [
                'condition' => [
                    'form!' => '',
                ],
                'default' => '',
                'label' => _x('Select the Field Name', 'admin-text', 'site-reviews-forms'),
                'label_block' => true,
                'options' => [],
                'type' => Controls_Manager::SELECT,
            ],
            'field_warning' => [
                'alert_type' => 'warning',
                'condition' => [
                    'form!' => '',
                    'field' => '',
                ],
                'content' => esc_html_x('The review form you selected has no custom rating or range fields.', 'admin-text', 'site-reviews-forms'),
                'type' => Controls_Manager::ALERT,
            ],
            'assigned_posts' => [
                'default' => '',
                'label' => _x('Limit Reviews to an Assigned Page', 'admin-text', 'site-reviews-forms'),
                'label_block' => true,
                'options' => $this->assigned_posts_options(),
                'type' => Controls_Manager::SELECT2,
            ],
            'assigned_posts_custom' => [
                'condition' => ['assigned_posts' => 'custom'],
                'description' => _x('Separate values with a comma.', 'admin-text', 'site-reviews-forms'),
                'label_block' => true,
                'placeholder' => _x('Enter the Post IDs', 'admin-text', 'site-reviews-forms'),
                'show_label' => false,
                'type' => Controls_Manager::TEXT,
            ],
            'assigned_terms' => [
                'default' => '',
                'label' => _x('Limit Reviews to an Assigned Category', 'admin-text', 'site-reviews-forms'),
                'label_block' => true,
                'multiple' => true,
                'options' => $this->assigned_terms_options(),
                'type' => Controls_Manager::SELECT2,
            ],
            'assigned_users' => [
                'default' => '',
                'label' => _x('Limit Reviews to an Assigned User', 'admin-text', 'site-reviews-forms'),
                'label_block' => true,
                'options' => $this->assigned_users_options(),
                'type' => Controls_Manager::SELECT2,
            ],
            'assigned_users_custom' => [
                'condition' => ['assigned_users' => 'custom'],
                'description' => _x('Separate values with a comma.', 'admin-text', 'site-reviews-forms'),
                'label_block' => true,
                'placeholder' => _x('Enter the User IDs', 'admin-text', 'site-reviews-forms'),
                'show_label' => false,
                'type' => Controls_Manager::TEXT,
            ],
            'terms' => [
                'default' => '',
                'label' => _x('Limit Reviews to terms', 'admin-text', 'site-reviews-forms'),
                'label_block' => true,
                'options' => [
                    'true' => _x('Terms were accepted', 'admin-text', 'site-reviews-forms'),
                    'false' => _x('Terms were not accepted', 'admin-text', 'site-reviews-forms'),
                ],
                'type' => Controls_Manager::SELECT2,
            ],
            'type' => $this->get_review_types(),
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

    protected function settings_style_label(): array
    {
        return [
            'label_color' => [
                'default' => '',
                'label' => esc_html_x('Color', 'admin-text', 'site-reviews-forms'),
                'global' => [
                    'default' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .glsr-field-summary__label' => 'color: {{VALUE}}',
                ],
                'type' => Controls_Manager::COLOR,
            ],
            'label_gap' => [
                'default' => [
                    'unit' => 'em',
                    'size' => 0.5,
                ],
                'is_responsive' => true,
                'label' => esc_html_x('Gap', 'admin-text', 'site-reviews-forms'),
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.125,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.125,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .glsr-field-summary' => '--glsr-gap-sm: {{SIZE}}{{UNIT}};',
                ],
                'size_units' => $this->set_custom_size_unit(['px', 'em', 'rem']),
                'type' => Controls_Manager::SLIDER,
            ],
            'label_typography' => [
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'group_control_type' => Group_Control_Typography::get_type(),
                'selector' => '{{WRAPPER}} .glsr-field-summary__label',
            ],
        ];
    }

    protected function settings_style_labels(): array
    {
        return [
            'labels_color' => [
                'default' => '',
                'label' => esc_html_x('Color', 'admin-text', 'site-reviews-forms'),
                'global' => [
                    'default' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .glsr-field-summary__bar' => 'color: {{VALUE}}',
                ],
                'type' => Controls_Manager::COLOR,
            ],
            'labels_gap' => [
                'default' => [
                    'unit' => 'em',
                    'size' => 0,
                ],
                'is_responsive' => true,
                'label' => esc_html_x('Gap', 'admin-text', 'site-reviews-forms'),
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.125,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.125,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .glsr-field-summary__bar::before' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'size_units' => $this->set_custom_size_unit(['px', 'em', 'rem']),
                'type' => Controls_Manager::SLIDER,
            ],
            'labels_typography' => [
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'group_control_type' => Group_Control_Typography::get_type(),
                'selector' => '{{WRAPPER}} .glsr-field-summary__bars',
            ],
        ];
    }

    protected function settings_style_value(): array
    {
        return [
            'bar_background_color' => [
                'default' => '',
                'global' => [
                    'default' => '',
                ],
                'label' => esc_html_x('Background Color', 'admin-text', 'site-reviews-forms'),
                'selectors' => [
                    '{{WRAPPER}} .glsr-field-summary__bar::before' => 'background-color: {{VALUE}};',
                ],
                'type' => Controls_Manager::COLOR,
            ],
            'bar_color' => [
                'default' => '',
                'global' => [
                    'default' => '',
                ],
                'label' => esc_html_x('Color', 'admin-text', 'site-reviews-forms'),
                'selectors' => [
                    '{{WRAPPER}} .glsr-field-summary__bars::before' => 'color: {{VALUE}};',
                ],
                'type' => Controls_Manager::COLOR,
            ],
            'bar_gap' => [
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'is_responsive' => true,
                'label' => esc_html_x('Gap', 'admin-text', 'site-reviews-forms'),
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .glsr-field-summary__bars' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'size_units' => ['px'],
                'type' => Controls_Manager::SLIDER,
            ],
            'bar_height' => [
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'is_responsive' => true,
                'label' => esc_html_x('Height', 'admin-text', 'site-reviews-forms'),
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .glsr-field-summary__bar::before' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .glsr-field-summary__bars::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'size_units' => ['px'],
                'type' => Controls_Manager::SLIDER,
            ],
        ];
    }
}
