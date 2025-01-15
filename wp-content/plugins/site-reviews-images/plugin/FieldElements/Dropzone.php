<?php

namespace GeminiLabs\SiteReviews\Addon\Images\FieldElements;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Modules\Html\FieldElements\AbstractFieldElement;

class Dropzone extends AbstractFieldElement
{
    public function build(array $overrideArgs = []): string
    {
        $types = glsr(Application::class)->option('mime_types', ['image/jpeg', 'image/png', 'image/webp'], 'array');
        $html = glsr(Application::class)->build('views/dropzone-field', [
            'name' => $this->field->name,
            'types' => implode(',', $types),
        ]);
        $inputArgs = [
            'name' => $this->field->name,
            'data-dz-images' => true,
            'data-glsr-validate' => true,
            'type' => 'hidden',
        ];
        if (!empty($this->field['data-conditions'])) {
            $inputArgs['data-conditions'] = $this->field['data-conditions'];
            unset($this->field['data-conditions']);
        }
        $input = $this->field->builder()->input($inputArgs);
        $this->field->text = $input.$html;
        $args = wp_parse_args($overrideArgs, $this->field->toArray());
        return $this->field->builder()->build($this->tag(), $args);
    }

    public function required(): array
    {
        $required = [
            'class' => 'glsr-dropzone',
        ];
        $min = glsr(Application::class)->option('min_files', 0);
        if ($min > 0) {
            $required['validation'] = "min:{$min}"; // minimum number of images in the array value
        }
        return $required;
    }

    public function tag(): string
    {
        return 'span';
    }
}
