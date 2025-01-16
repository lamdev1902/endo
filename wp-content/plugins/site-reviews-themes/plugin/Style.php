<?php

namespace GeminiLabs\SiteReviews\Addon\Themes;

use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Str;

class Style
{
    public const PREFIX = '--gl-';

    public array $only;
    public array $properties;
    public int $themeId;

    public function __construct(int $themeId = 0)
    {
        $this->only = [];
        $this->properties = [];
        $this->themeId($themeId);
    }

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return static
     */
    public function get()
    {
        $settings = $this->settings();
        if (empty($settings)) {
            return $this;
        }
        foreach ($settings as $group => $sections) {
            foreach ($sections as $section => $values) {
                foreach ($values as $key => $value) {
                    // @todo
                    $this->build($key, $value);
                }
            }
        }
        $properties = array_filter($this->properties);
        natsort($properties);
        $this->properties = $properties;
        return $this;
    }

    /**
     * @return static
     */
    public function only(array $propertyKeys)
    {
        $this->only = $propertyKeys;
        return $this;
    }

    public function properties(): array
    {
        if (empty($this->properties)) {
            $this->get();
        }
        if (!empty($this->only)) {
            return array_intersect_key($this->properties, array_flip($this->only));
        }
        return $this->properties;
    }

    public function settings(): array
    {
        $config = glsr(Application::class)->config('theme-settings');
        $config = array_filter($config, function ($value) {
            return isset($value['theme']);
        });
        $settings = glsr(ThemeSettings::class)->themeId($this->themeId)->toArray();
        $settings = Arr::flatten($settings);
        $settings = array_intersect_key($settings, $config);
        $settings = Arr::unflatten($settings);
        return $settings;
    }

    public function toArray(): array
    {
        return $this->properties();
    }

    public function themeId(int $postId): self
    {
        if (Application::POST_TYPE !== get_post_type($postId)) {
            $postId = 0;
        }
        $this->themeId = $postId;
        return $this;
    }

    public function toString(): string
    {
        return implode('', $this->properties());
    }

    /**
     * @param string|int $value
     */
    public function build(string $key, $value): void
    {
        $patterns = [
            'dimensions' => '/^(-?\d+\|){4}px\|\d+$/', // 0|0|0|0|px|1
            'typography' => '/^(-?\d+\|){2}px\|((\#|rgb).+)?$/', // 0|0|px|#000
            'box-shadow' => '/^(-?\d+\|){4}((\#|rgb).+)?$/', // 0|0|0|0|#000
            'rating-colors' => '/^((\#|rgb)[^\|]+\|){6}\d+$/', // #000|#000|#000|#000|#000|#000|1
        ];
        foreach ($patterns as $type => $regex) {
            if (1 === preg_match($regex, $value)) {
                $method = Helper::buildMethodName('build', $type);
                call_user_func([$this, $method], $key, $value);
                return;
            }
        }
        $this->buildDefault($key, $value);
    }

    public function slideMargin(): string
    {
        return str_replace('padding', 'margin', $this->slidePadding());
    }

    public function slidePadding(): string
    {
        $shadow1 = $this->getShadow(1);
        $shadow2 = $this->getShadow(2);
        $values = [];
        foreach ($shadow1 as $key => $value) {
            $values[$key] = $value > $shadow2[$key] ? $value : $shadow2[$key];
        }
        $size = $values['blur'] + $values['spread'];
        $top = max(0, $size - $values['y']);
        $bottom = max(0, $size + $values['y']);
        $style = sprintf('padding-top:%spx;padding-bottom:%spx;', $top, $bottom);
        return str_replace('0px', '0', $style);
    }

    /**
     * @param string|int $value
     */
    protected function buildDefault(string $key, $value): void
    {
        if (is_numeric($value)) {
            $value = sprintf('%spx', $value);
        }
        if (empty($value) && str_ends_with($key, '_color')) {
            $value = 'transparent';
        }
        $property = $this->property($key);
        $this->properties[$property] = sprintf('%s:%s;', $property, $value);
    }

    /**
     * @param string|int $value
     */
    protected function buildDimensions(string $key, $value): void
    {
        $values = explode('|', $value);
        $values = array_combine(['top', 'right', 'bottom', 'left', 'unit'], array_slice($values, 0, 5));
        $property = $this->property($key);
        if (count(array_filter($values)) > 1) {
            $this->properties[$property] = sprintf('%s:%s %s %s %s;',
                $property,
                $values['top'].$values['unit'],
                $values['right'].$values['unit'],
                $values['bottom'].$values['unit'],
                $values['left'].$values['unit']
            );
        } else {
            $this->properties[$property] = sprintf('%s:0;', $property);
        }
    }

    /**
     * @param string|int $value
     */
    protected function buildTypography(string $key, $value): void
    {
        $values = explode('|', $value);
        $values = array_pad($values, 4, '');
        $values = array_combine(['fontSize', 'lineHeight', 'unit', 'color'], $values);
        $suffix = str_replace('text_', '', $key);
        if ('' !== $values['color']) {
            $property = $this->property('color', $suffix);
            $this->properties[$property] = sprintf('%s:%s;',
                $property,
                str_replace(' ', '', $values['color'])
            );
        }
        if ('' !== $values['fontSize']) {
            $property = $this->property('font-size', $suffix);
            $this->properties[$property] = sprintf('%s:%s%s;',
                $property,
                $values['fontSize'],
                $values['unit']
            );
        }
        if ('' !== $values['lineHeight']) {
            $property = $this->property('line-height', $suffix);
            $this->properties[$property] = sprintf('%s:%s%s;',
                $property,
                $values['lineHeight'],
                $values['unit']
            );
        }
    }

    /**
     * @param string|int $value
     */
    protected function buildBoxShadow(string $key, $value): void
    {
        $values = explode('|', $value);
        $values = array_pad($values, 4, '');
        $values = array_combine(['x', 'y', 'blur', 'spread', 'color'], $values);
        $property = $this->property('box-'.$key);
        if (count(array_filter($values)) > 1 && '' !== $values['color']) {
            $this->properties[$property] = sprintf('%s:%spx %spx %spx %spx %s;',
                $property,
                $values['x'],
                $values['y'],
                $values['blur'],
                $values['spread'],
                str_replace(' ', '', $values['color'])
            );
        } else {
            $this->properties[$property] = sprintf('%s:none;', $property);
        }
    }

    /**
     * @param string|int $value
     */
    protected function buildRatingColors(string $key, $value): void
    {
        $values = explode('|', $value);
        $values = array_slice($values, 0, 6);
        foreach ($values as $rating => $color) {
            $property = $this->property('rating-color', $rating);
            $this->properties[$property] = sprintf('%s:%s;', $property, $color);
        }
    }

    protected function getShadow(int $num): array
    {
        $num = max(1, min(2, $num));
        $data = glsr(ThemeSettings::class)->themeId($this->themeId)->get('design.appearance.shadow_'.$num);
        $values = explode('|', $data);
        $values = array_slice(array_pad($values, 4, '0'), 0, 4);
        $values = array_map('intval', $values);
        $values = array_combine(['x', 'y', 'blur', 'spread'], $values);
        return $values;
    }

    /**
     * @param string|int $suffix
     */
    protected function property(string $key, $suffix = null): string
    {
        if (!is_null($suffix)) {
            $suffix = Str::prefix(Str::dashcase((string) $suffix), '-');
        }
        return Str::prefix(Str::suffix(Str::dashcase($key), (string) $suffix), static::PREFIX);
    }
}
