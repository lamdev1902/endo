<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications;

use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Modules\Queue as BaseQueue;

class Queue extends BaseQueue
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }
}
