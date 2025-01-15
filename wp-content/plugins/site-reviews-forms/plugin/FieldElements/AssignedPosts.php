<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\FieldElements;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\FieldElements\AbstractFieldElement;

class AssignedPosts extends AbstractFieldElement
{
    public function build(array $overrideArgs = []): string
    {
        $overrideArgs['options'] = $this->selectOptions();
        return parent::build($overrideArgs);
    }

    public function required(): array
    {
        if (!Cast::toBool($this->field->hidden)) {
            return [];
        }
        return [
            'is_raw' => true,
            'original_type' => 'hidden',
            'type' => 'hidden',
            'value' => $this->field->value,
        ];
    }

    public function tag(): string
    {
        return Cast::toBool($this->field->hidden) ? 'input' : 'select';
    }

    protected function selectOptions(): array
    {
        $args = [
            'no_found_rows' => true, // skip counting the total rows found
            'post_status' => 'publish',
            'post_type' => Arr::consolidate($this->field->posttypes),
            'posts_per_page' => 100,
            'suppress_filters' => true,
        ];
        $args = glsr(Application::class)->filterArray('builder/assigned_posts/args', $args, $this->field);
        $posts = get_posts($args);
        $options = wp_list_pluck($posts, 'post_title', 'ID');
        $options = array_filter(array_unique($options));
        array_walk($options, 'esc_html');
        natcasesort($options); // sort the results
        return $options;
    }
}
