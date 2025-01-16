<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\FieldElements;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\FieldElements\AbstractFieldElement;
use GeminiLabs\SiteReviews\Modules\Sanitizer;

class AssignedUsers extends AbstractFieldElement
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
            'value' => implode(',', Arr::consolidate($this->field->users)),
        ];
    }

    public function tag(): string
    {
        return Cast::toBool($this->field->hidden) ? 'input' : 'select';
    }

    protected function selectOptions(): array
    {
        $args = [
            'fields' => [
                'ID',
                'display_name',
            ],
            'orderby' => 'display_name',
            'role__in' => Arr::consolidate($this->field->roles),
        ];
        $args = glsr(Application::class)->filterArray('builder/assigned_users/args', $args, $this->field);
        $users = get_users($args);
        $options = wp_list_pluck($users, 'display_name', 'ID');
        array_walk($options, function (&$displayName) {
            $displayName = glsr(Sanitizer::class)->sanitizeUserName($displayName);
        });
        return $options;
    }
}
