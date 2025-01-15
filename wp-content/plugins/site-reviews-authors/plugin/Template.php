<?php

namespace GeminiLabs\SiteReviews\Addon\Authors;

use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Modules\Html\Template as DefaultTemplate;

class Template extends DefaultTemplate
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }
}
