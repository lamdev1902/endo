<?php

namespace GeminiLabs\SiteReviews\Addon\Themes;

use GeminiLabs\SiteReviews\Addon\Themes\Defaults\HtmlAttributesDefaults;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Modules\Html\Builder;

class Html
{
    protected array $attributes = [];

    public function __construct()
    {
        $this->attributes = Arr::unflatten(
            glsr(HtmlAttributesDefaults::class)->defaults()
        );
    }

    public function build(int $themeId): string
    {
        if ($data = glsr(ThemeBuilder::class)->themeId($themeId)->toArray()) {
            $el = glsr()->args(Arr::get($data, 0));
            $html = glsr(Builder::class)->div(wp_parse_args($this->attributes($el), [
                'text' => $this->children($el->children),
            ]));
            $attributes = [
                'class' => 'glsr-review',
                'data-assigned' => '{{ assigned }}',
                'id' => 'review-{{ review_id }}',
                'text' => $html,
            ];
            if ('carousel' !== glsr(ThemeSettings::class)->themeId($themeId)->get('presentation.layout.display_as')) {
                $attributes['id'] = 'review-{{ review_id }}';
                return glsr(Builder::class)->div($attributes);
            }
            $attributes['data-id'] = '{{ review_id }}';
            $attributes['style'] = glsr(Style::class)->themeId($themeId)->slideMargin();
            $classes = 'gl-slide';
            if ('splide' === glsr(Application::ID)->option('swiper_library', 'swiper')) {
                $classes = sprintf('splide__slide %s', $classes);
            }
            return glsr(Builder::class)->div([
                'class' => $classes,
                'text' => glsr(Builder::class)->div($attributes),
            ]);
        }
        return '';
    }

    public function container(Arguments $el): string
    {
        $args = wp_parse_args($this->attributes($el), [
            'text' => $this->children($el->children),
        ]);
        return glsr(Builder::class)->div($args);
    }

    public function field(Arguments $el): string
    {
        $args = wp_parse_args($this->attributes($el), [
            'data-tag' => $el->tag,
            'text' => sprintf('{{ %s }}', $el->tag),
        ]);
        return glsr(Builder::class)->div($args);
    }

    /**
     * @param scalar $value
     */
    protected function attribute(string $attr, string $key, $value): string
    {
        $path = sprintf('%s.%s', $attr, $key);
        if (is_string($value) && !is_numeric($value)) {
            $path = sprintf('%s.%s', $path, $value);
        }
        $attribute = Arr::get($this->attributes, $path);
        if (!empty($attribute) && !empty($value)) {
            if ('class' === $attr) {
                return $attribute;
            }
            if ('style' === $attr) {
                return sprintf($attribute, $value);
            }
        }
        return '';
    }

    protected function attributes(Arguments $el): array
    {
        $class = [];
        $style = [];
        foreach ($el as $key => $value) {
            $class[] = $this->attribute('class', $key, $value);
            $style[] = $this->attribute('style', $key, $value);
        }
        return [
            'class' => implode(' ', array_filter($class)),
            'style' => implode(' ', array_filter($style)),
        ];
    }

    protected function children(array $data): string
    {
        $children = [];
        foreach ($data as $child) {
            $el = glsr()->args($child);
            if ($el->tag) {
                $children[] = $this->field($el);
            } elseif ($el->children) {
                $children[] = $this->container($el);
            }
        }
        return implode('', $children);
    }
}
