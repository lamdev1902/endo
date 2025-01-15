<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications;

use GeminiLabs\Pelago\Emogrifier\CssInliner;
use GeminiLabs\Pelago\Emogrifier\HtmlProcessor\CssToAttributeConverter;
use GeminiLabs\Pelago\Emogrifier\HtmlProcessor\HtmlPruner;
use GeminiLabs\SiteReviews\Addon\Notifications\Defaults\EmailDefaults;
use GeminiLabs\SiteReviews\Addon\Notifications\Defaults\SettingsDefaults;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Contracts\TemplateContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;
use GeminiLabs\SiteReviews\Helpers\Color;
use GeminiLabs\SiteReviews\Modules\Email as BaseEmail;

class Email extends BaseEmail
{
    /** @var Arguments */
    public $settings;

    /** @var string */
    public $css;

    public function __construct()
    {
        $this->settings = glsr(Application::ID)->options(SettingsDefaults::class);
    }

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    public function defaults(): DefaultsAbstract
    {
        return glsr(EmailDefaults::class);
    }

    public function template(): TemplateContract
    {
        return glsr(Template::class);
    }

    protected function buildHtmlMessage(): string
    {
        $css = $this->inlineStyles();
        $html = $this->template()->build('templates/emails/'.$this->email['template'], [
            'context' => $this->email['template-tags'],
        ]);
        try {
            $cssInliner = CssInliner::fromHtml($html)->inlineCss($css);
            HtmlPruner::fromDomDocument($cssInliner->getDomDocument())
                ->removeElementsWithDisplayNone()
                ->removeRedundantClassesAfterCssInlined($cssInliner);
            CssToAttributeConverter::fromDomDocument($cssInliner->getDomDocument())
                ->convertCssToVisualAttributes();
            $message = $cssInliner->render();
        } catch (\Exception $e) {
            glsr_log()->error('Emogrifer: '.$e->getMessage());
            $style = sprintf('<style type="text/css">%s</style>', $css);
            $message = str_replace('</head>', $style.'</head>', $html);
        }
        return $this->app()->filterString('email/message', stripslashes($message), 'html', $this);
    }

    protected function buildMessage(): string
    {
        return '';
    }

    protected function colors(): array
    {
        $colors = [
            'background_color' => $this->settings->background_color,
            'body_background_color' => $this->settings->body_background_color,
            'body_link_color' => $this->settings->body_link_color,
            'body_text_color' => $this->settings->body_text_color,
            'brand_color' => $this->settings->brand_color,
            'footer_text_color' => '',
            'header_text_color' => '',
        ];
        $color = Color::new($colors['background_color']);
        if (!is_wp_error($color)) {
            if ($color->isLight()) {
                $colors['footer_text_color'] = (string) $color->mix('#000', .25)->toHex();
            } else {
                $colors['footer_text_color'] = (string) $color->mix('#fff', .75)->toHex();
            }
        }
        $color = Color::new($colors['brand_color']);
        if (!is_wp_error($color)) {
            if ($color->isLight()) {
                $colors['header_text_color'] = (string) $color->mix('#000', .85)->toHex();
            } else {
                $colors['header_text_color'] = (string) $color->mix('#fff', .85)->toHex();
            }
        }
        return array_map('esc_attr', $colors);
    }

    protected function inlineStyles(): string
    {
        return $this->template()->build('templates/styles/'.$this->email['style'], [
            'context' => $this->colors(),
        ]);
    }
}
