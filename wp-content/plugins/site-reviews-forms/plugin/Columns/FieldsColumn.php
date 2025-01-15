<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Columns;

use GeminiLabs\SiteReviews\Addon\Forms\FormFields;

class FieldsColumn extends Column
{
    public function build(string $value = ''): string
    {
        $fields = glsr(FormFields::class)->indexedFields($this->postId);
        return (string) count($fields);
    }
}
