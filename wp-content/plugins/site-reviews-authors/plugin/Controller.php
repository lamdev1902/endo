<?php

namespace GeminiLabs\SiteReviews\Addon\Authors;

use GeminiLabs\SiteReviews\Addon\Authors\Commands\DeleteReview;
use GeminiLabs\SiteReviews\Addon\Authors\Commands\RespondToReview;
use GeminiLabs\SiteReviews\Addon\Authors\Commands\UpdateReview;
use GeminiLabs\SiteReviews\Addon\Authors\Forms\DeleteReviewForm;
use GeminiLabs\SiteReviews\Addon\Authors\Forms\RespondToReviewForm;
use GeminiLabs\SiteReviews\Addon\Authors\Forms\UpdateReviewForm;
use GeminiLabs\SiteReviews\Addon\Authors\MockTags\DeleteUrlTag;
use GeminiLabs\SiteReviews\Addon\Authors\MockTags\EditUrlTag;
use GeminiLabs\SiteReviews\Addon\Authors\MockTags\RespondUrlTag;
use GeminiLabs\SiteReviews\Addon\Authors\Tags\ReviewAuthorActionsTag;
use GeminiLabs\SiteReviews\Addon\Authors\Tags\ReviewDeleteUrlTag;
use GeminiLabs\SiteReviews\Addon\Authors\Tags\ReviewEditUrlTag;
use GeminiLabs\SiteReviews\Addon\Authors\Tags\ReviewRespondUrlTag;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\SiteReviewsFormDefaults;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\ReviewHtml;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Modules\Style;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;
use GeminiLabs\SiteReviews\Shortcodes\SiteReviewsFormShortcode;

class Controller extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @action site-reviews/route/ajax/delete-review
     */
    public function actionDeleteReviewAjax(Request $request): void
    {
        $review = glsr_get_review($request->review_id);
        if (!$this->app()->canDeleteOnFrontend($review)) { // @phpstan-ignore-line
            wp_send_json_error([
                'message' => __('You do not have permission to delete the review.', 'site-reviews-authors'),
            ]);
        }
        $command = $this->execute(new DeleteReview($request));
        $command->sendJsonResponse();
    }

    /**
     * @action site-reviews/route/ajax/respond-to-review
     */
    public function actionRespondToReviewAjax(Request $request): void
    {
        $review = glsr_get_review($request->review_id);
        if (!$this->app()->canRespondOnFrontend($review)) { // @phpstan-ignore-line
            wp_send_json_error([
                'message' => __('You do not have permission to respond to the review.', 'site-reviews-authors'),
            ]);
        }
        $command = $this->execute(new RespondToReview($request));
        $command->sendJsonResponse();
    }

    /**
     * @action site-reviews/route/ajax/update-review
     */
    public function actionUpdateReviewAjax(Request $request): void
    {
        $review = glsr_get_review($request->review_id);
        if (!$this->app()->canEditOnFrontend($review)) { // @phpstan-ignore-line
            wp_send_json_error([
                'message' => __('You do not have permission to update the review.', 'site-reviews-authors'),
            ]);
        }
        $command = $this->execute(new UpdateReview($request));
        $command->sendJsonResponse();
    }

    /**
     * @action site-reviews/route/ajax/fetch-delete-review-form
     */
    public function fetchDeleteReviewFormAjax(Request $request): void
    {
        $review = glsr_get_review($request->review_id);
        if (!$review->isValid()) {
            wp_send_json_error([
                'message' => esc_html__('The review could not be found.', 'site-reviews-authors'),
            ]);
        }
        if (!$this->app()->canDeleteOnFrontend($review)) { // @phpstan-ignore-line
            wp_send_json_error([
                'message' => esc_html__('You do not have permission to delete the review.', 'site-reviews-authors'),
            ]);
        }
        $form = new DeleteReviewForm($review);
        wp_send_json_success([
            'class' => glsr(Style::class)->styleClasses(),
            'content' => $form->build(),
            'footer' => '',
            'header' => sprintf(__('Delete Review <span>ID: %d</span>', 'site-reviews-authors'), $review->ID),
        ]);
    }

    /**
     * @action site-reviews/route/ajax/fetch-respond-to-review-form
     */
    public function fetchRespondToReviewFormAjax(Request $request): void
    {
        $review = glsr_get_review($request->review_id);
        if (!$review->isValid()) {
            wp_send_json_error([
                'message' => esc_html__('The review could not be found.', 'site-reviews-authors'),
            ]);
        }
        if (!$this->app()->canRespondOnFrontend($review)) { // @phpstan-ignore-line
            wp_send_json_error([
                'message' => esc_html__('You do not have permission to respond to the review.', 'site-reviews-authors'),
            ]);
        }
        $args = [
            'form' => $request->cast('form', 'int'), // $review->form
            'response' => $review->response,
            'theme' => $request->cast('theme', 'int'),
        ];
        $atts = glsr(SiteReviewsFormShortcode::class)->attributes($args);
        $form = new RespondToReviewForm($review, $args);
        wp_send_json_success([
            'class' => glsr(Style::class)->styleClasses(),
            'content' => $form->build(),
            'footer' => '',
            'header' => sprintf(__('Responding To Review <span>ID: %d</span>', 'site-reviews-authors'), $review->ID),
        ]);
    }

    /**
     * @action site-reviews/route/ajax/fetch-update-review-form
     */
    public function fetchUpdateReviewFormAjax(Request $request): void
    {
        $review = glsr_get_review($request->review_id);
        if (!$review->isValid()) {
            wp_send_json_error([
                'message' => esc_html__('The review could not be found.', 'site-reviews-authors'),
            ]);
        }
        if (!$this->app()->canEditOnFrontend($review)) { // @phpstan-ignore-line
            wp_send_json_error([
                'message' => esc_html__('You do not have permission to update the review.', 'site-reviews-authors'),
            ]);
        }
        $args = glsr(SiteReviewsFormDefaults::class)->merge([
            'hide' => $review->meta()->array('_excluded'),
            'form' => $request->cast('form', 'int'), // $review->form
            'theme' => $request->cast('theme', 'int'),
        ]);
        $atts = glsr(SiteReviewsFormShortcode::class)->attributes($args);
        $form = new UpdateReviewForm($review, $args);
        wp_send_json_success([
            'class' => glsr(Style::class)->styleClasses(),
            'content' => $form->build(),
            'footer' => '',
            'header' => sprintf(__('Editing Review <span>ID: %d</span>', 'site-reviews-authors'), $review->ID),
            'images' => $form->images(),
            'style' => Arr::get($atts, 'style'),
        ]);
    }

    /**
     * @filter site-reviews/captcha/actions
     */
    public function filterCaptchaActions(array $actions): array
    {
        $actions[] = 'delete-review';
        $actions[] = 'respond-to-review';
        $actions[] = 'update-review';
        return $actions;
    }

    /**
     * @filter site-reviews/defaults/custom-fields/guarded
     */
    public function filterGuardedCustomFields(array $guarded): array
    {
        $guarded[] = 'author_actions';
        $guarded[] = 'delete_url';
        $guarded[] = 'edit_url';
        $guarded[] = 'respond_url';
        return Arr::unique($guarded);
    }

    /**
     * @filter site-reviews/shortcode/hide-options
     */
    public function filterHideOptions(array $options, string $shortcode): array
    {
        if (in_array($shortcode, ['site_review', 'site_reviews'])) {
            $options['delete_url'] = _x('Hide the delete link', 'admin-text', 'site-reviews-authors');
            $options['edit_url'] = _x('Hide the edit link', 'admin-text', 'site-reviews-authors');
            $options['respond_url'] = _x('Hide the respond link', 'admin-text', 'site-reviews-authors');
        }
        return $options;
    }

    /**
     * @filter site-reviews/enqueue/public/inline-styles
     */
    public function filterInlineStyles(string $styles): string
    {
        $styles .= file_get_contents($this->app()->path('assets/inline.css'));
        return $styles;
    }

    /**
     * @filter site-reviews-themes/mock/tag/delete_url
     */
    public function filterMockDeleteUrlTag(): string
    {
        return DeleteUrlTag::class;
    }

    /**
     * @filter site-reviews-themes/mock/tag/edit_url
     */
    public function filterMockEditUrlTag(): string
    {
        return EditUrlTag::class;
    }

    /**
     * @filter site-reviews-themes/mock/tag/respond_url
     */
    public function filterMockRespondUrlTag(): string
    {
        return RespondUrlTag::class;
    }

    /**
     * @filter quick_edit_enabled_for_post_type
     */
    public function filterRenderDeletionReason(bool $isQuickEditEnabled, string $postType): bool
    {
        if (glsr()->post_type !== $postType) {
            return $isQuickEditEnabled;
        }
        if ('trash' !== get_post_status()) {
            return $isQuickEditEnabled;
        }
        $reason = Cast::toString(get_post_meta(get_the_ID(), '_wp_trash_meta_reason', true));
        if (empty($reason)) {
            return $isQuickEditEnabled;
        }
        $userId = get_post_meta(get_the_ID(), '_wp_trash_meta_user', true);
        if ($user = get_user_by('id', $userId)) {
            $displayName = glsr(Sanitizer::class)->sanitizeUserName($user->display_name);
            $userUrl = sprintf('<a href="%s">%s</a>', get_edit_user_link($userId), $displayName);
            $userUrl = sprintf(_x('Deleted by %s.', 'admin-text', 'site-reviews-authors'), $userUrl);
            $tooltip = glsr(Builder::class)->span([
                'class' => 'glsr-tooltip dashicons-before dashicons-editor-help',
                'data-tippy-allowhtml' => 1,
                'data-tippy-content' => esc_js($userUrl),
                'data-tippy-delay' => '[200,null]',
                'data-tippy-interactive' => 1,
                'data-tippy-offset' => '[-10,10]',
                'data-tippy-placement' => 'top-start',
                'style' => 'float:none;',
            ]);
            $reason = $tooltip.$reason;
        }
        echo glsr(Builder::class)->div([
            'class' => 'glsr-notice-inline components-notice is-error',
            'style' => 'align-items:baseline; column-gap:4px; padding:8px;',
            'text' => $reason,
        ]);
        return $isQuickEditEnabled;
    }

    /**
     * @filter site-reviews/defaults/review/defaults
     */
    public function filterReviewDefaults(array $defaults): array
    {
        $defaults['delete_url'] = '';
        $defaults['edit_url'] = '';
        $defaults['respond_url'] = '';
        return $defaults;
    }

    /**
     * @filter site-reviews/review/tag/author_actions
     */
    public function filterReviewAuthorActionsTag(): string
    {
        return ReviewAuthorActionsTag::class;
    }

    /**
     * @filter site-reviews/review/tag/delete_url
     */
    public function filterReviewDeleteUrlTag(): string
    {
        return ReviewDeleteUrlTag::class;
    }

    /**
     * @filter site-reviews/review/tag/edit_url
     */
    public function filterReviewEditUrlTag(): string
    {
        return ReviewEditUrlTag::class;
    }

    /**
     * @filter site-reviews/review/tag/respond_url
     */
    public function filterReviewRespondUrlTag(): string
    {
        return ReviewRespondUrlTag::class;
    }

    /**
     * @filter site-reviews/defaults/review/sanitize
     */
    public function filterReviewSanitizers(array $sanitize): array
    {
        $sanitize['delete_url'] = 'url';
        $sanitize['edit_url'] = 'url';
        $sanitize['respond_url'] = 'url';
        return $sanitize;
    }

    /**
     * @filter site-reviews/build/template/review
     */
    public function filterReviewTemplate(string $template, array $data = []): string
    {
        $hasAuthorActions = str_contains($template, '{{ author_actions }}');
        $hasDeleteUrl = str_contains($template, '{{ delete_url }}');
        $hasEditUrl = str_contains($template, '{{ edit_url }}');
        $hasRespondUrl = str_contains($template, '{{ respond_url }}');
        if (!$hasAuthorActions && !$hasRespondUrl && !$hasEditUrl && !$hasDeleteUrl) {
            $template = str_replace('{{ response }}', '{{ response }} {{ author_actions }}', $template);
            return $template;
        }
        if (!$hasRespondUrl) {
            $template = str_replace('{{ response }}', '{{ response }} {{ respond_url }}', $template);
        }
        if (!$hasEditUrl) {
            $template = str_replace('{{ respond_url }}', '{{ respond_url }} {{ edit_url }}', $template);
        }
        if (!$hasDeleteUrl) {
            $template = str_replace('{{ edit_url }}', '{{ edit_url }} {{ delete_url }}', $template);
        }
        return $template;
    }

    /**
     * @filter site-reviews/review/build/after
     */
    public function filterReviewTemplateTags(array $tags, Review $review, ReviewHtml $html): array
    {
        $tags['author_actions'] = $html->buildTemplateTag($review, 'author_actions', '');
        return $tags;
    }

    /**
     * @filter site-reviews/settings/sanitize
     */
    public function filterSettingSanitization(array $options, array $input): array
    {
        $keys = [
            'settings.addons.authors.roles',
            'settings.addons.authors.delete_roles',
            'settings.addons.authors.respond_to_roles',
        ];
        foreach ($keys as $key) {
            $roles = Arr::get($input, $key, []);
            $options = Arr::set($options, $key, $roles);
        }
        return $options;
    }

    /**
     * @filter site-reviews-themes/config/templates/template_1
     */
    public function filterThemeBuilderTemplate(array $config): array
    {
        return $this->app()->config('themes/templates/template_1');
    }

    /**
     * @filter site-reviews-themes/defaults/tag/defaults
     */
    public function filterThemeTagDefaults(array $defaults): array
    {
        $defaults['delete_url'] = 'review_delete_url';
        $defaults['edit_url'] = 'review_edit_url';
        $defaults['respond_url'] = 'review_respond_url';
        ksort($defaults);
        return $defaults;
    }

    /**
     * @filter site-reviews/router/public/unguarded-actions
     */
    public function filterUnguardedActions(array $actions): array
    {
        $actions[] = 'fetch-delete-review-form';
        $actions[] = 'fetch-respond-to-review-form';
        $actions[] = 'fetch-update-review-form';
        return $actions;
    }

    /**
     * @filter site-reviews/validate/duplicate
     * @filter site-reviews/validate/review-limits
     */
    public function filterValidation(bool $result): bool
    {
        $action = Arr::get(Helper::filterInputArray(glsr()->id), '_action');
        $actions = [
            'fetch-delete-review-form',
            'fetch-respond-to-review-form',
            'fetch-update-review-form',
        ];
        if (in_array($action, $actions)) {
            return true;
        }
        return $result;
    }

    /**
     * @action untrashed_post
     */
    public function removeDeletionReason(int $postId): void
    {
        if (glsr()->post_type === get_post_type($postId)) {
            delete_post_meta($postId, '_wp_trash_meta_reason');
            delete_post_meta($postId, '_wp_trash_meta_user');
        }
    }

    /**
     * @action site-reviews/get/review
     */
    public function setReviewDeleteUrl(Review $review): void
    {
        if (empty($review->delete_url)) {
            $object = get_post_type_object(glsr()->post_type);
            $link = admin_url(sprintf("{$object->_edit_link}&amp;action=trash", $review->ID));
            $link = wp_nonce_url($link, "trash-post_{$review->ID}");
            $link = apply_filters('get_delete_post_link', $link, $review->ID, false);
            $review->set('delete_url', $link);
        }
    }

    /**
     * @action site-reviews/get/review
     */
    public function setReviewEditUrlAndRespondUrl(Review $review): void
    {
        $tags = [
            'edit_url',
            'respond_url',
        ];
        foreach ($tags as $tag) {
            if (!empty($review[$tag])) {
                continue;
            }
            $object = get_post_type_object(glsr()->post_type);
            $link = admin_url(sprintf("{$object->_edit_link}&amp;action=edit", $review->ID));
            $link = apply_filters('get_edit_post_link', $link, $review->ID, 'display');
            $review->set($tag, $link);
        }
    }
}
