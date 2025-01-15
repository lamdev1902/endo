<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\FieldElements;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\FieldElements\AbstractFieldElement;

class AssignedTerms extends AbstractFieldElement
{
    /**
     * This method is used when building a custom Field type.
     */
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
            'value' => implode(',', Arr::consolidate($this->field->terms)),
        ];
    }

    public function tag(): string
    {
        return Cast::toBool($this->field->hidden) ? 'input' : 'select';
    }

    protected function removeDuplicates(array $options): array
    {
        $children = array_values(array_map('array_keys', array_filter($options, 'is_array')));
        $children = Arr::uniqueInt(call_user_func_array('array_merge', $children));
        foreach ($options as $termId => $termIds) {
            if (in_array($termId, $children)) {
                unset($options[$termId]);
            }
        }
        return $options;
    }

    protected function selectOptions(): array
    {
        return glsr()->filterBool('builder/enable/optgroup', true)
            ? $this->termGroups()
            : $this->terms();
    }

    protected function termGroups(): array
    {
        $options = [];
        $terms = $this->terms('all');
        foreach ($terms as $term) {
            $children = array_filter($terms, function ($child) use ($term) {
                return $term->term_id === $child->parent;
            });
            if (empty($children)) {
                $options[$term->term_id] = $term->name;
                continue;
            }
            $options[$term->name] = [];
            foreach ($children as $child) {
                $options[$term->name][$child->term_id] = $child->name;
            }
        }
        return $this->removeDuplicates($options);
    }

    protected function terms(string $fields = 'id=>name'): array
    {
        $args = [
            'count' => false,
            'fields' => $fields,
            'hide_empty' => false,
            'include' => Arr::consolidate($this->field->terms),
            'taxonomy' => glsr()->taxonomy,
        ];
        $args = glsr(Application::class)->filterArray('builder/assigned_terms/args', $args, $this->field);
        if ('id=>name' === $fields) {
            $args['fields'] = 'id=>name'; // ensure this is correct
        }
        return get_terms($args);
    }
}
