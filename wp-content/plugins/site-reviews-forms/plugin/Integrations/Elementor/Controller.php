<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Integrations\Elementor;

use Elementor\Controls_Manager;
use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\FormFields;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Request;

class Controller extends AbstractController
{
    /**
     * @action site-reviews/route/ajax/elementor-widget-form-fields
     */
    public function elementorWidgetFormFieldsAjax(Request $request): void
    {
        $formId = $request->cast('form_id', 'int');
        $fields = glsr(FormFields::class)->normalizedFieldsIndexed($formId);
        $fields = array_filter($fields,
            fn ($field) => in_array($field['type'], ['range', 'rating']) && 'rating' !== $field['tag']
        );
        $fields = array_map(fn ($field) => [
            'label' => sprintf('%s (%s)', $field['label'] ?: $field['handle'], $field['name']),
            'value' => $field['name'],
        ], $fields);
        if (empty($fields)) {
            $fields[] = [
                'label' => '',
                'value' => '',
            ];
        }
        wp_send_json_success(compact('fields'));
    }

    /**
     * @param \Elementor\Widget_Base $widget
     *
     * @filter site-reviews/elementor/register/controls
     */
    public function filterElementorWidgetControls(array $sections, $widget): array
    {
        if (!in_array($widget->get_name(), ['site_review', 'site_reviews', 'site_reviews_form'])) {
            return $sections;
        }
        $option = [
            'default' => '',
            'label_block' => true,
            'options' => glsr(Application::class)->posts(),
            'type' => Controls_Manager::SELECT2,
        ];
        if ('site_reviews' === $widget->get_name()) {
            $option['label'] = _x('Use a Custom Form Review Template', 'admin-text', 'site-reviews-forms');
        } else {
            $option['label'] = _x('Use a Custom Form', 'admin-text', 'site-reviews-forms');
        }
        $controls = $sections['settings']['controls'];
        if ('site_review' === $widget->get_name() && array_key_exists('post_id', $controls)) {
            $controls['post_id']['separator'] = 'after';
            $controls = Arr::insertAfter('post_id', $controls, ['form' => $option]);
        } else {
            $controls = Arr::prepend($controls, $option, 'form');
        }
        $sections['settings']['controls'] = $controls;
        return $sections;
    }

    /**
     * @param $manager \Elementor\Widgets_Manager
     *
     * @action elementor/widgets/register
     */
    public function registerElementorWidgets($manager): void
    {
        $manager->register(new ElementorFieldSummaryWidget());
    }

    /**
     * @action elementor/editor/after_enqueue_styles
     * @action elementor/preview/enqueue_styles
     */
    public function registerInlineStyles(): void
    {
        $css = "
            .elementor-control.elementor-control-type-select:not(.elementor-hidden-control) {
                display: block;
            }
            .eicon-glsr-field::before {
                background-color: currentColor;
                content: '.';
                display: block;
                width: 1em;
            }
            .eicon-glsr-field::before {
                -webkit-mask-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M14 5c4.803 0 8.727 3.762 8.986 8.5H28v1l-5.014.001C22.726 19.238 18.802 23 14 23s-8.726-3.762-8.986-8.499L0 14.5v-1h5.014C5.273 8.762 9.197 5 14 5zm0 1a8 8 0 1 0 0 16 8 8 0 1 0 0-16zm.023 2.788c.224 0 .476.112.476.476l1.26 2.436 2.8.364c.252 0 .364.112.476.336 0 .224 0 .476-.112.588l-2.1 1.988.476 2.8c0 .224 0 .448-.252.588-.112.112-.336.112-.588 0l-2.436-1.288-2.576 1.148c-.112.14-.336.14-.588 0-.224-.112-.224-.336-.224-.56l.476-2.8-1.988-1.988c-.112-.112-.224-.364-.112-.588.112-.112.224-.364.448-.364l2.8-.336 1.288-2.464c0-.224.252-.336.476-.336zm0 1.876l-.924 1.736c-.14.224-.252.364-.476.364l-1.876.224 1.4 1.288c.075.075.1.149.108.257l.004.191-.336 1.876 1.736-.924c.252-.112.476-.112.588 0l1.764.924-.364-1.876c-.112-.112 0-.336.112-.448l1.4-1.288-1.848-.224c-.168-.093-.274-.174-.359-.251l-.117-.113-.812-1.736z'/%3E%3C/svg%3E\");
                        mask-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M14 5c4.803 0 8.727 3.762 8.986 8.5H28v1l-5.014.001C22.726 19.238 18.802 23 14 23s-8.726-3.762-8.986-8.499L0 14.5v-1h5.014C5.273 8.762 9.197 5 14 5zm0 1a8 8 0 1 0 0 16 8 8 0 1 0 0-16zm.023 2.788c.224 0 .476.112.476.476l1.26 2.436 2.8.364c.252 0 .364.112.476.336 0 .224 0 .476-.112.588l-2.1 1.988.476 2.8c0 .224 0 .448-.252.588-.112.112-.336.112-.588 0l-2.436-1.288-2.576 1.148c-.112.14-.336.14-.588 0-.224-.112-.224-.336-.224-.56l.476-2.8-1.988-1.988c-.112-.112-.224-.364-.112-.588.112-.112.224-.364.448-.364l2.8-.336 1.288-2.464c0-.224.252-.336.476-.336zm0 1.876l-.924 1.736c-.14.224-.252.364-.476.364l-1.876.224 1.4 1.288c.075.075.1.149.108.257l.004.191-.336 1.876 1.736-.924c.252-.112.476-.112.588 0l1.764.924-.364-1.876c-.112-.112 0-.336.112-.448l1.4-1.288-1.848-.224c-.168-.093-.274-.174-.359-.251l-.117-.113-.812-1.736z'/%3E%3C/svg%3E\");
                -webkit-mask-repeat: no-repeat;
                        mask-repeat: no-repeat;
            }
        ";
        wp_add_inline_style('elementor-editor', $css);
        wp_add_inline_style('elementor-frontend', $css."
            .eicon-glsr-field::before {
                font-size: 28px;
                margin: 0 auto;
            }
        ");
    }

    /**
     * @action elementor/editor/after_enqueue_scripts
     */
    public function registerScripts(): void
    {
        wp_enqueue_script(
            glsr(Application::class)->id.'/elementor',
            glsr(Application::class)->url('assets/elementor-editor.js'),
            [],
            glsr(Application::class)->version,
            ['strategy' => 'defer']
        );
        wp_localize_script(glsr(Application::class)->id.'/elementor', 'GLSR_Elementor', [
            'action' => glsr()->prefix.'admin_action',
            'nameprefix' => glsr()->id,
            'nonce' => [
                'elementor-widget-form-fields' => wp_create_nonce('elementor-widget-form-fields'),
            ],
        ]);
    }
}
