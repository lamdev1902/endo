<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Controllers;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Addon\Actions\Commands\ReportReview;
use GeminiLabs\SiteReviews\Addon\Actions\Commands\ShareReview;
use GeminiLabs\SiteReviews\Addon\Actions\Commands\UpvoteReview;
use GeminiLabs\SiteReviews\Addon\Actions\Database\TableActionsLog;
use GeminiLabs\SiteReviews\Addon\Actions\MockTags\ActionsTag;
use GeminiLabs\SiteReviews\Addon\Actions\ReportReviewForm;
use GeminiLabs\SiteReviews\Addon\Actions\Tags\ReviewActionsTag;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Install;
use GeminiLabs\SiteReviews\Modules\Html\ReviewHtml;
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
     * @action site-reviews/route/ajax/fetch-report-review-form
     */
    public function fetchReportReviewFormAjax(Request $request): void
    {
        $review = glsr_get_review($request->review_id);
        if (!$review->isValid()) {
            wp_send_json_error([
                'message' => __('The review could not be found.', 'site-reviews-actions'),
                'title' => __('Review missing', 'site-reviews-actions'),
            ]);
        }
        if (!$this->app()->canReportReview()) { // @phpstan-ignore-line
            $loginLink = glsr(SiteReviewsFormShortcode::class)->loginLink();
            wp_send_json_error([
                'message' => sprintf(__('You must be %s to report a review.', 'site-reviews-actions'), $loginLink),
                'title' => __('Not allowed', 'site-reviews-actions'),
            ]);
        }
        $form = new ReportReviewForm($review);
        wp_send_json_success([
            'classes' => glsr(Style::class)->styleClasses(),
            'content' => $form->build(),
            'footer' => '',
            'header' => __('Why are you reporting this review?', 'site-reviews-actions'),
        ]);
    }

    /**
     * @action site-reviews/route/ajax/fetch-share-review-form
     */
    public function fetchShareReviewFormAjax(Request $request): void
    {
        // wp_send_json_success([]);
        // wp_send_json_error([]);
    }

    /**
     * @filter site-reviews/captcha/actions
     */
    public function filterCaptchaActions(array $actions): array
    {
        $actions[] = 'report-review';
        return $actions;
    }

    /**
     * @filter site-reviews/database/tables
     */
    public function filterDatabaseTables(array $tables): array
    {
        $tables[] = TableActionsLog::class;
        return $tables;
    }

    /**
     * @filter site-reviews/shortcode/hide-options
     */
    public function filterHideOptions(array $options, string $shortcode): array
    {
        if (in_array($shortcode, ['site_review', 'site_reviews'])) {
            $options['actions'] = _x('Hide the review actions', 'admin-text', 'site-reviews-actions');
        }
        return $options;
    }

    /**
     * @filter site-reviews/enqueue/admin/inline-styles
     */
    public function filterInlineAdminStyles(string $styles): string
    {
        $styles .= file_get_contents($this->app()->path('assets/inline-admin.css'));
        return $styles;
    }

    /**
     * @filter site-reviews/enqueue/public/inline-styles
     */
    public function filterInlinePublicStyles(string $styles): string
    {
        $styles .= file_get_contents($this->app()->path('assets/inline.css'));
        return $styles;
    }

    /**
     * @filter site-reviews-themes/mock/tag/actions
     */
    public function filterMockActionsTag(): string
    {
        return ActionsTag::class;
    }

    /**
     * @filter site-reviews/build/template/review
     */
    public function filterReviewTemplate(string $template, array $data = []): string
    {
        if (!str_contains($template, '{{ actions }}')) {
            $template = str_replace('{{ response }}', '{{ actions }} {{ response }}', $template);
        }
        return $template;
    }

    /**
     * @filter site-reviews/settings/sanitize
     */
    public function filterSettingSanitization(array $options, array $input): array
    {
        $key = 'settings.addons.actions';
        $roles = Arr::get($input, "{$key}.buttons", []);
        $options = Arr::set($options, "{$key}.buttons", $roles);
        if ('' === trim(Arr::get($input, "{$key}.report_confirmation"))) {
            $defaultValue = Arr::get(glsr()->defaults(), "{$key}.report_confirmation");
            $options = Arr::set($options, "{$key}.report_confirmation", $defaultValue);
        }
        if ('' === trim(Arr::get($input, "{$key}.report_notification"))) {
            $defaultValue = Arr::get(glsr()->defaults(), "{$key}.report_notification");
            $options = Arr::set($options, "{$key}.report_notification", $defaultValue);
        }
        return $options;
    }

    /**
     * @filter site-reviews/review/tag/actions
     */
    public function filterTemplateTag(string $className): string
    {
        return ReviewActionsTag::class;
    }

    /**
     * @filter site-reviews/review/build/after
     */
    public function filterTemplateTags(array $tags, Review $review, ReviewHtml $html): array
    {
        $tags['actions'] = $html->buildTemplateTag($review, 'actions', '');
        return $tags;
    }

    /**
     * @filter site-reviews-themes/defaults/tag/defaults
     */
    public function filterThemeTagDefaults(array $defaults): array
    {
        $defaults['actions'] = 'review_actions';
        ksort($defaults);
        return $defaults;
    }

    /**
     * @filter site-reviews/router/public/unguarded-actions
     */
    public function filterUnguardedActions(array $actions): array
    {
        $actions[] = 'fetch-report-review-form';
        $actions[] = 'fetch-share-review-form';
        $actions[] = 'report-review';
        $actions[] = 'share-review';
        $actions[] = 'translate-review';
        $actions[] = 'upvote-review';
        return $actions;
    }

    /**
     * @action {$this->app()->id}/activated
     */
    public function install(): void
    {
        parent::install();
        require_once ABSPATH.'wp-admin/includes/plugin.php';
        if (!is_plugin_active_for_network($this->app()->basename)) {
            glsr(TableActionsLog::class)->create();
            glsr(TableActionsLog::class)->addForeignConstraints();
            return;
        }
        foreach (glsr(Install::class)->sites() as $siteId) {
            switch_to_blog($siteId);
            glsr(TableActionsLog::class)->create();
            glsr(TableActionsLog::class)->addForeignConstraints();
            restore_current_blog();
        }
    }

    /**
     * @action site-reviews/route/ajax/report-review
     */
    public function reportReviewAjax(Request $request): void
    {
        $review = glsr_get_review($request->review_id);
        if (!$review->isValid()) {
            wp_send_json_error([
                'message' => __('The review could not be found.', 'site-reviews-actions'),
                'title' => __('Review missing', 'site-reviews-actions'),
            ]);
        }
        if (!$this->app()->canReportReview()) { // @phpstan-ignore-line
            $loginLink = glsr(SiteReviewsFormShortcode::class)->loginLink();
            wp_send_json_error([
                'message' => sprintf(__('You must be %s to report a review.', 'site-reviews-actions'), $loginLink),
                'title' => __('Not allowed', 'site-reviews-actions'),
            ]);
        }
        $command = $this->execute(new ReportReview($request));
        if ($command->successful()) {
            wp_send_json_success($command->response());
        }
        wp_send_json_error($command->response());
    }

    /**
     * @action site-reviews/route/ajax/share-review
     */
    public function shareReviewAjax(Request $request): void
    {
        $command = $this->execute(new ShareReview($request));
        if ($command->successful()) {
            wp_send_json_success($command->response());
        }
        wp_send_json_error($command->response());
    }

    /**
     * @action deactivate_{$this->app()->basename}
     */
    public function onDeactivation(bool $isNetworkDeactivation): void
    {
        parent::onDeactivation($isNetworkDeactivation);
        if (!$isNetworkDeactivation) {
            glsr(TableActionsLog::class)->dropForeignConstraints();
            return;
        }
        foreach (glsr(Install::class)->sites() as $siteId) {
            switch_to_blog($siteId);
            glsr(TableActionsLog::class)->dropForeignConstraints();
            restore_current_blog();
        }
    }

    /**
     * @action site-reviews/route/ajax/upvote-review
     */
    public function upvoteReviewAjax(Request $request): void
    {
        $command = $this->execute(new UpvoteReview($request));
        $response = $command->response();
        wp_send_json_success($response);
    }
}
