<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Shortcodes;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\CustomField;
use GeminiLabs\SiteReviews\Addon\Forms\Database\Query;
use GeminiLabs\SiteReviews\Addon\Forms\Template;
use GeminiLabs\SiteReviews\Contracts\FieldContract;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Rating;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Shortcodes\Shortcode;

class SiteReviewsFieldShortcode extends Shortcode
{
    public array $args = [];

    protected FieldContract $field;
    protected array $values;

    public function buildTemplate(): string
    {
        $average = glsr(Rating::class)->average($this->values, 2);
        $count = array_sum($this->values);
        $max = max(1, count($this->field->options));
        $percent = ($average / $max) * 100;
        $percent = Cast::toFloat($percent);
        $this->debug([
            'average' => $average,
            'field' => $this->field,
            'percent' => $percent,
            'values' => $this->values,
        ]);
        if ($this->isEmpty()) {
            return glsr(Application::class)->filterString('field-summary/if_empty', '');
        }
        $html = glsr(Template::class)->build('templates/field-summary', [
            'args' => $this->args,
            'context' => [
                'class' => $this->getClasses(),
                'count' => $count,
                'label' => $this->buildTemplateLabel(),
                'value' => $this->buildTemplateValue($percent),
            ],
            'field' => $this->field,
            'percent' => $percent,
        ]);
        return glsr(Template::class)->minify($html);
    }

    /**
     * @return static
     */
    public function normalize(array $args, string $type = '')
    {
        parent::normalize($args, $type);
        $this->field = new CustomField($this->args);
        $this->values = $this->percentValues();
        return $this;
    }

    protected function buildTemplateLabel(): string
    {
        if (in_array('label', $this->args['hide'])) {
            return '';
        }
        $html = glsr(Builder::class)->div([
            'class' => 'glsr-field-summary__label',
            'text' => $this->field->label ?: $this->field->tag_label,
        ]);
        return glsr(Application::class)->filterString('field-summary/build/label', $html, $this->field, $this);
    }

    protected function buildTemplateValue(float $percent): string
    {
        $this->field->labels = $this->percentLabels();
        $labels = array_reduce($this->field->labels, function ($carry, $label) {
            if (in_array('labels', $this->args['hide'])) {
                $label = '';
            }
            return $carry.glsr(Builder::class)->div([
                'class' => 'glsr-field-summary__bar',
                'text' => esc_html($label),
            ]);
        });
        $html = glsr(Builder::class)->div([
            'class' => 'glsr-field-summary__bars',
            'style' => "--glsr-field-summary-percent:{$percent}%;",
            'text' => $labels,
        ]);
        return glsr(Application::class)->filterString('field-summary/build/value', $html, $this->field, $percent, $this);
    }

    protected function getClasses(): string
    {
        $classes = ['glsr-field-summary'];
        $classes[] = $this->args['class'];
        $classes = implode(' ', $classes);
        return glsr(Sanitizer::class)->sanitizeAttrClass($classes);
    }

    protected function hideOptions(): array
    {
        return [
            'if_empty' => _x('Hide if no values are found', 'admin-text', 'site-reviews-forms'),
            'label' => _x('Hide label', 'admin-text', 'site-reviews-forms'),
            'labels' => _x('Hide labels', 'admin-text', 'site-reviews-forms'),
        ];
    }

    protected function isEmpty(): bool
    {
        return !$this->field->is_valid || (
            empty(array_sum($this->values)) && in_array('if_empty', $this->args['hide'])
        );
    }

    protected function percentLabels(): array
    {
        $labels = $this->field->labels;
        if (!empty($this->field->labels) && 1 < count($this->field->labels)) {
            return $labels;
        }
        $labels = [];
        $options = $this->field->options;
        ksort($options, \SORT_NUMERIC);
        $numOfLabels = (0 === count($options) % 2) ? 2 : 3;
        if ($numOfLabels > count($options)) {
            return $labels;
        }
        $labels[] = current($options);
        if (3 === $numOfLabels) {
            $index = (int) ceil(count($options) / 2);
            $labels[] = $options[$index] ?? '';
        }
        $labels[] = end($options);
        return $labels;
    }

    protected function percentValues(): array
    {
        if (!isset($this->field)) {
            return [];
        }
        if (!$this->field->is_valid) {
            return [];
        }
        $max = max(1, count($this->field->options));
        $min = 1;
        $results = glsr(Query::class)->fields($this->args);
        $values = [];
        array_walk_recursive($results, function ($value, $index) use (&$values) {
            $values[$index] = $value + intval(Arr::get($values, $index, 0));
        });
        foreach ($values as $index => &$value) {
            if (!Helper::inRange($index, $min, $max)) {
                $value = 0;
            }
        }
        return $values;
    }
}
