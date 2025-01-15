<?php

namespace GeminiLabs\SiteReviews\Addon\Themes;

use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;

class ThemeSettings extends ThemeBuilder
{
    /**
     * @param string $path
     * @param mixed $fallback
     * @return mixed
     */
    public function get($path, $fallback = null)
    {
        if (empty($this->data)) {
            $this->refresh();
        }
        return Arr::get($this->data, $path, $fallback);
    }

    /**
     * @return static
     */
    public function refresh()
    {
        if (Application::POST_TYPE === get_post_type($this->themeId)) {
            $data = Arr::consolidate(get_post_meta($this->themeId, $this->metakey(), true));
            $this->store($data);
        }
        return $this;
    }

    /**
     * Return the sections with the settings inserted.
     */
    public function settings(): array
    {
        $settings = Arr::unflatten($this->sections());
        if (empty($this->toArray())) { // get and store the saved settings
            return $settings;
        }
        $config = Arr::unflatten(glsr(Application::class)->config('theme-settings'));
        foreach ($config as $group => $sections) {
            foreach ($sections as $section => $fields) {
                if (!array_key_exists($group, $settings)) {
                    continue; // give priority to the predefined sections
                }
                foreach ($fields as $name => $field) {
                    $default = Arr::get($field, 'default');
                    $path = $group.'.'.$section;
                    $field['group'] = $group;
                    $field['name'] = $name;
                    $field['section'] = $section;
                    $field['value'] = Arr::get($this->data, $path.'.'.$name, $default);
                    $settings = Arr::set($settings, $path.'.controls.'.$name, $field);
                }
            }
        }
        return $settings;
    }

    /**
     * @return static
     */
    public function store(array $settings = [])
    {
        $data = [];
        $groups = Arr::unflatten($this->sections());
        foreach ($groups as $group => $sections) {
            foreach ($sections as $section => $control) {
                $className = Helper::buildClassName([$group, $section, 'defaults'], 'Addon\Themes\Defaults');
                $className = glsr(Application::class)->filterString("settings/$group/$section/classname", $className, $settings);
                if (class_exists($className)) {
                    $path = $group.'.'.$section;
                    $values = Arr::consolidate(Arr::get($settings, $path));
                    $values = glsr($className)->restrict($values);
                    $data = Arr::set($data, $path, $values);
                }
            }
        }
        $this->data = $data;
        return $this;
    }

    protected function sections(): array
    {
        $sections = [
            'presentation.layout' => [
                'dashicon' => 'dashicons-screenoptions',
                'label' => _x('Layout', 'admin-text', 'site-reviews-themes'),
            ],
            'presentation.excerpt' => [
                'dashicon' => 'dashicons-text',
                'label' => _x('Review Excerpts', 'admin-text', 'site-reviews-themes'),
            ],
            'design.appearance' => [
                'dashicon' => 'dashicons-editor-table',
                'label' => _x('Appearance', 'admin-text', 'site-reviews-themes'),
            ],
            'design.avatar' => [
                'dashicon' => 'dashicons-admin-users',
                'label' => _x('Avatar', 'admin-text', 'site-reviews-themes'),
            ],
            'design.rating' => [
                'dashicon' => 'dashicons-star-filled',
                'label' => _x('Rating', 'admin-text', 'site-reviews-themes'),
            ],
            'design.typography' => [
                'dashicon' => 'dashicons-editor-paragraph',
                'label' => _x('Typography', 'admin-text', 'site-reviews-themes'),
            ],
        ];
        return glsr(Application::class)->filterArray('settings/sections', $sections, $this);
    }
}
