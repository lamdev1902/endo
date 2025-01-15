<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Controllers;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\FormFields;
use GeminiLabs\SiteReviews\Addon\Forms\ReviewTemplate;
use GeminiLabs\SiteReviews\Addon\Forms\Tags\CustomTag;
use GeminiLabs\SiteReviews\Contracts\TagContract;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\HookProxy;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\ReviewHtml;
use GeminiLabs\SiteReviews\Review;

class TemplateController
{
    use HookProxy;

    /**
     * @filter site-reviews/enqueue/public/inline-styles
     */
    public function filterInlineStyles(string $stylesheet): string
    {
        $inlineStylesheetPath = glsr(Application::class)->path('assets/inline.css');
        $stylesheet .= file_get_contents($inlineStylesheetPath);
        if ('choices.js' === glsr_get_option('addons.forms.dropdown_library')) {
            $inlinePath = glsr(Application::class)->path('assets/inline-choices.js.css');
            $stylesheet .= file_get_contents($inlinePath);
        }
        return $stylesheet;
    }

    /**
     * @filter site-reviews/build/template/review
     */
    public function filterReviewTemplate(string $template, array $data): string
    {
        $formId = Arr::getAs('int', $data, 'args.form');
        if ($customTemplate = glsr(ReviewTemplate::class)->template($formId)) {
            return $customTemplate;
        }
        return $template;
    }

    /**
     * Render the templates tags of custom fields in the review.
     * @filter site-reviews/review/build/after
     */
    public function filterReviewTemplateTags(array $templateTags, Review $review, ReviewHtml $reviewHtml): array
    {
        $args = $reviewHtml->args;
        $formId = Arr::getAs('int', $reviewHtml->args, 'form');
        $fields = glsr(FormFields::class)->customFields($formId);
        $customTags = glsr(FormFields::class)->customTemplateTags($formId);
        foreach ($customTags as $name => $tag) {
            $field = glsr()->args(Arr::get($fields, $name));
            $value = Helper::ifEmpty($review->custom[$name], '');
            if (Helper::isNotEmpty($value) && !$field->isEmpty()) {
                $type = $field->type;
                if (is_array($value)) {
                    $value = implode(',', $value);
                }
                $className = Helper::buildClassName(['custom', $type, 'tag'], 'Addon\Forms\Tags');
                $className = glsr(Application::class)->filterString('custom/tag/'.$type, $className, $reviewHtml);
                $className = Helper::ifTrue(class_exists($className), $className, CustomTag::class);
                $value = glsr($className, compact('tag', 'field', 'args'))->handleFor('custom', $value, $review);
            }
            $templateTags[$tag] = $value;
        }
        return $templateTags;
    }

    /**
     * @filter site-reviews/custom/wrapped
     * @filter site-reviews/review/wrapped
     */
    public function filterWrappedTagValue(string $value, string $rawValue, TagContract $tag): string
    {
        $label = Arr::get($tag->with, 'tag_label');
        if (empty($label) && ($tag->with instanceof Review)) {
            $formId = Arr::getAs('int', $tag->args, 'form');
            $fields = glsr(FormFields::class)->indexedFields($formId);
            foreach ($fields as $field) {
                if ($tag->tag === Arr::get($field, 'tag')) {
                    $label = Arr::get($field, 'tag_label');
                    break;
                }
            }
        }
        if (empty($label)) {
            return $value;
        }
        $label = glsr(Builder::class)->span([
            'class' => 'glsr-tag-label',
            'text' => $label,
        ]);
        return $label.$value;
    }
}
