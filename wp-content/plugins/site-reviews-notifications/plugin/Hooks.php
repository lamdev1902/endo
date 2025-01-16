<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications;

use GeminiLabs\SiteReviews\Addon\Notifications\Controllers\Controller;
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
            ['filterExportSettingsData', 'site-reviews/export/settings/extra'],
            ['filterLocalizedAdminVariables', 'site-reviews/enqueue/admin/localize'],
            ['filterSettingsCallback', 'site-reviews/settings/sanitize', 10, 2],
            ['notificationsAjax', 'site-reviews/route/ajax/notifications'],
            ['onImportSettings', 'site-reviews/import/settings/extra'],
            ['queueAfterCreated', 'site-reviews/review/created', 20, 2],
            ['queueAfterResponded', 'site-reviews/review/responded', 10, 2],
            ['queueAfterStatusChange', 'site-reviews/review/approved', 10, 3],
            ['queueAfterStatusChange', 'site-reviews/review/unapproved', 10, 3],
            ['queueAfterVerified', 'site-reviews/review/verified'],
            ['renderTemplates', 'admin_footer-site-review_page_glsr-settings'],
            ['sendNotification', 'site-reviews-notifications/queue/notification', 10, 2],
            ['sendTestNotificationAjax', 'site-reviews/route/ajax/send-test-notification'],
        ]));
    }
}
