<?php

namespace GeminiLabs\SiteReviews\Addon\Authors;

use GeminiLabs\SiteReviews\Addons\Hooks as AddonHooks;
use GeminiLabs\SiteReviews\Contracts\PluginContract;

class Hooks extends AddonHooks
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    public function run(): void
    {
        $this->hook(Controller::class, $this->baseHooks([
            ['actionDeleteReviewAjax', 'site-reviews/route/ajax/delete-review'],
            ['actionRespondToReviewAjax', 'site-reviews/route/ajax/respond-to-review'],
            ['actionUpdateReviewAjax', 'site-reviews/route/ajax/update-review'],
            ['fetchDeleteReviewFormAjax', 'site-reviews/route/ajax/fetch-delete-review-form'],
            ['fetchRespondToReviewFormAjax', 'site-reviews/route/ajax/fetch-respond-to-review-form'],
            ['fetchUpdateReviewFormAjax', 'site-reviews/route/ajax/fetch-update-review-form'],
            ['filterCaptchaActions', 'site-reviews/captcha/actions'],
            ['filterGuardedCustomFields', 'site-reviews/defaults/custom-fields/guarded'],
            ['filterHideOptions', 'site-reviews/shortcode/hide-options', 10, 2],
            ['filterInlineStyles', 'site-reviews/enqueue/public/inline-styles'],
            ['filterMockDeleteUrlTag', 'site-reviews-themes/mock/tag/delete_url'],
            ['filterMockEditUrlTag', 'site-reviews-themes/mock/tag/edit_url'],
            ['filterMockRespondUrlTag', 'site-reviews-themes/mock/tag/respond_url'],
            ['filterRenderDeletionReason', 'quick_edit_enabled_for_post_type', 10, 2],
            ['filterReviewDefaults', 'site-reviews/defaults/review/defaults'],
            ['filterReviewAuthorActionsTag', 'site-reviews/review/tag/author_actions'],
            ['filterReviewDeleteUrlTag', 'site-reviews/review/tag/delete_url'],
            ['filterReviewEditUrlTag', 'site-reviews/review/tag/edit_url'],
            ['filterReviewRespondUrlTag', 'site-reviews/review/tag/respond_url'],
            ['filterReviewSanitizers', 'site-reviews/defaults/review/sanitize'],
            ['filterReviewTemplate', 'site-reviews/build/template/review', 10, 2],
            ['filterReviewTemplateTags', 'site-reviews/review/build/after', 10, 3],
            ['filterSettingSanitization', 'site-reviews/settings/sanitize', 10, 2],
            ['filterThemeBuilderTemplate', 'site-reviews-themes/config/templates/template_1'],
            ['filterThemeTagDefaults', 'site-reviews-themes/defaults/tag/defaults'],
            ['filterUnguardedActions', 'site-reviews/router/public/unguarded-actions'],
            ['filterValidation', 'site-reviews/validate/duplicate'],
            ['filterValidation', 'site-reviews/validate/review-limits'],
            ['removeDeletionReason', 'untrashed_post'],
            ['setReviewDeleteUrl', 'site-reviews/get/review'],
            ['setReviewEditUrlAndRespondUrl', 'site-reviews/get/review'],
        ]));
    }
}
