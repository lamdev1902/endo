<?php

namespace GeminiLabs\SiteReviews\Addon\Themes;

use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Str;

class ThemeBuilder
{
    public array $data;
    public int $themeId;

    public function __construct(int $themeId = 0)
    {
        $this->data = [];
        $this->themeId($themeId);
    }

    public function default(): array
    {
        return glsr(Application::class)->config('templates/template_1');
    }

    public function metakey(): string
    {
        $name = (new \ReflectionClass($this))->getShortName();
        $name = Str::snakeCase($name);
        return "_{$name}";
    }

    /**
     * @return static
     */
    public function refresh()
    {
        if (Application::POST_TYPE === get_post_type($this->themeId)) {
            $data = Arr::consolidate(get_post_meta($this->themeId, $this->metakey(), true));
            if (empty($data)) {
                $data = $this->default();
            }
            $this->store($data);
        }
        return $this;
    }

    public function save(array $data = []): bool
    {
        if (Application::POST_TYPE === get_post_type($this->themeId)) {
            $this->store($data);
            update_post_meta($this->themeId, $this->metakey(), $this->toArray());
            return true;
        }
        return false;
    }

    /**
     * @return static
     */
    public function store(array $data = [])
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return static
     */
    public function themeId(int $postId)
    {
        if (Application::POST_TYPE !== get_post_type($postId)) {
            $postId = 0;
        }
        $this->themeId = $postId;
        return $this;
    }

    public function toArray(): array
    {
        if (empty($this->data)) {
            $this->refresh();
        }
        return $this->data;
    }
}
