<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Controllers;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Addon\Actions\Commands\ChangeReviewLanguage;
use GeminiLabs\SiteReviews\Addon\Actions\Commands\DetectLanguage;
use GeminiLabs\SiteReviews\Addon\Actions\Commands\TranslateReview;
use GeminiLabs\SiteReviews\Addon\Actions\Defaults\LanguagesDefaults;
use GeminiLabs\SiteReviews\Addon\Actions\Template;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;

class TranslateController extends AbstractController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @action site-reviews/route/ajax/change-language
     */
    public function changeLanguageAjax(Request $request): void
    {
        $command = $this->execute(new ChangeReviewLanguage($request));
        wp_send_json_success($command->response());
    }

    /**
     * @action site-reviews/review/created
     */
    public function detectLanguage(Review $review): void
    {
        $this->execute(new DetectLanguage($review));
    }

    /**
     * @filter site-reviews/enqueue/admin/localize
     */
    public function filterAdminLocalizedVariables(array $variables): array
    {
        $variables['nonce'] ??= [];
        $variables['nonce']['change-language'] = wp_create_nonce('change-language');
        return $variables;
    }

    /**
     * @action post_submitbox_misc_actions
     */
    public function renderMiscActions(\WP_Post $post): void
    {
        if (!Review::isReview($post)) {
            return;
        }
        $review = glsr_get_review($post->ID);
        if (!$review->isValid()) {
            return;
        }
        glsr(Template::class)->render('views/editor/language', [
            'language' => $review->meta()->get('_language', 'en'),
            'languages' => glsr(LanguagesDefaults::class)->defaults(),
        ]);
    }

    /**
     * @action site-reviews/route/ajax/translate-review
     */
    public function translateReviewAjax(Request $request): void
    {
        $command = $this->execute(new TranslateReview($request));
        wp_send_json_success($command->response());
    }
}
