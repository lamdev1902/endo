<?php

namespace GeminiLabs\SiteReviews\Addon\Actions;

use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Contracts\TemplateContract;
use GeminiLabs\SiteReviews\Modules\Email as BaseEmail;

class Email extends BaseEmail
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }
}
