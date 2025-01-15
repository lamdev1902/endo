<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Defaults;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class HtmlAttributesDefaults extends DefaultsAbstract
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'class.align.center' => 'gl-items-center',
            'class.align.end' => 'gl-items-end',
            'class.align.start' => 'gl-items-start',
            'class.align.stretch' => 'gl-items-stretch',
            'class.direction.col' => 'gl-flex gl-flex-col',
            'class.direction.row' => 'gl-flex gl-flex-row',
            'class.flex.grow' => 'gl-flex-1',
            'class.flex.none' => 'gl-flex-none',
            'class.flex.shrink' => 'gl-flex-0',
            'class.is_bold' => 'gl-bold',
            'class.is_hidden' => 'gl-hidden',
            'class.is_italic' => 'gl-italic',
            'class.is_uppercase' => 'gl-uppercase',
            'class.text.large' => 'gl-text-large',
            'class.text.normal' => 'gl-text-normal',
            'class.text.small' => 'gl-text-small',
            'class.wrap' => 'gl-flex-wrap',
            'style.gap' => 'gap:%spx;',
            'style.minwidth' => 'min-width:%spx;',
        ];
    }
}
