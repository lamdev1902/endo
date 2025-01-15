<?php

namespace GeminiLabs\SiteReviews\Addon\Forms;

use GeminiLabs\SiteReviews\Addon\Forms\FormFields;
use GeminiLabs\SiteReviews\Modules\Html\ReviewField;

class CustomField extends ReviewField
{
    public function __construct(array $args = [])
    {
        $args = wp_parse_args($args, [
            'field' => '',
            'form' => 0,
        ]);
        $fields = glsr(FormFields::class)->normalizedFieldsKeyed($args['form']);
        $field = $fields[$args['field']] ?? [];
        parent::__construct($field);
    }

    protected function validate(): bool
    {
        $requiredKeys = array_filter([
            'name' => empty($this->name),
            'type' => empty($this->type),
        ]);
        $this->is_valid = empty($requiredKeys);
        return $this->is_valid;
    }
}
