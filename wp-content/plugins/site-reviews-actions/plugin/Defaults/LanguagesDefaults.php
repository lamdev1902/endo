<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Defaults;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;

class LanguagesDefaults extends DefaultsAbstract
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'ar' => esc_html_x('Arabic', 'admin-text', 'site-reviews-actions'),
            'bg' => esc_html_x('Bulgarian', 'admin-text', 'site-reviews-actions'),
            'zh' => esc_html_x('Chinese', 'admin-text', 'site-reviews-actions'),
            'cs' => esc_html_x('Czech', 'admin-text', 'site-reviews-actions'),
            'da' => esc_html_x('Danish', 'admin-text', 'site-reviews-actions'),
            'nl' => esc_html_x('Dutch', 'admin-text', 'site-reviews-actions'),
            'en' => esc_html_x('English', 'admin-text', 'site-reviews-actions'),
            'et' => esc_html_x('Estonian', 'admin-text', 'site-reviews-actions'),
            'fi' => esc_html_x('Finnish', 'admin-text', 'site-reviews-actions'),
            'fr' => esc_html_x('French', 'admin-text', 'site-reviews-actions'),
            'de' => esc_html_x('German', 'admin-text', 'site-reviews-actions'),
            'el' => esc_html_x('Greek', 'admin-text', 'site-reviews-actions'),
            'hu' => esc_html_x('Hungarian', 'admin-text', 'site-reviews-actions'),
            'id' => esc_html_x('Indonesian', 'admin-text', 'site-reviews-actions'),
            'it' => esc_html_x('Italian', 'admin-text', 'site-reviews-actions'),
            'ja' => esc_html_x('Japanese', 'admin-text', 'site-reviews-actions'),
            'ko' => esc_html_x('Korean', 'admin-text', 'site-reviews-actions'),
            'lv' => esc_html_x('Latvian', 'admin-text', 'site-reviews-actions'),
            'lt' => esc_html_x('Lithuanian', 'admin-text', 'site-reviews-actions'),
            'nb' => esc_html_x('Norwegian Bokmål', 'admin-text', 'site-reviews-actions'),
            'pl' => esc_html_x('Polish', 'admin-text', 'site-reviews-actions'),
            'pt' => esc_html_x('Portuguese', 'admin-text', 'site-reviews-actions'),
            'ro' => esc_html_x('Romanian', 'admin-text', 'site-reviews-actions'),
            'ru' => esc_html_x('Russian', 'admin-text', 'site-reviews-actions'),
            'sk' => esc_html_x('Slovak', 'admin-text', 'site-reviews-actions'),
            'sl' => esc_html_x('Slovenian', 'admin-text', 'site-reviews-actions'),
            'es' => esc_html_x('Spanish', 'admin-text', 'site-reviews-actions'),
            'sv' => esc_html_x('Swedish', 'admin-text', 'site-reviews-actions'),
            'tr' => esc_html_x('Turkish', 'admin-text', 'site-reviews-actions'),
            'uk' => esc_html_x('Ukrainian', 'admin-text', 'site-reviews-actions'),
            // 'ar' => esc_html_x('Arabic (العربية)', 'admin-text', 'site-reviews-actions'),
            // 'bg' => esc_html_x('Bulgarian (Български)', 'admin-text', 'site-reviews-actions'),
            // 'zh' => esc_html_x('Chinese (简体中文)', 'admin-text', 'site-reviews-actions'),
            // 'cs' => esc_html_x('Czech (Čeština)', 'admin-text', 'site-reviews-actions'),
            // 'da' => esc_html_x('Danish (Dansk)', 'admin-text', 'site-reviews-actions'),
            // 'nl' => esc_html_x('Dutch (Nederlands)', 'admin-text', 'site-reviews-actions'),
            // 'en' => esc_html_x('English', 'admin-text', 'site-reviews-actions'),
            // 'et' => esc_html_x('Estonian (Eesti)', 'admin-text', 'site-reviews-actions'),
            // 'fi' => esc_html_x('Finnish (Suomi)', 'admin-text', 'site-reviews-actions'),
            // 'fr' => esc_html_x('French (Français)', 'admin-text', 'site-reviews-actions'),
            // 'de' => esc_html_x('German (Deutsch)', 'admin-text', 'site-reviews-actions'),
            // 'el' => esc_html_x('Greek (Ελληνικά)', 'admin-text', 'site-reviews-actions'),
            // 'hu' => esc_html_x('Hungarian (Magyar)', 'admin-text', 'site-reviews-actions'),
            // 'id' => esc_html_x('Indonesian (Bahasa Indonesia)', 'admin-text', 'site-reviews-actions'),
            // 'it' => esc_html_x('Italian (Italiano)', 'admin-text', 'site-reviews-actions'),
            // 'ja' => esc_html_x('Japanese (日本語)', 'admin-text', 'site-reviews-actions'),
            // 'ko' => esc_html_x('Korean (한국어)', 'admin-text', 'site-reviews-actions'),
            // 'lv' => esc_html_x('Latvian (Latviešu valoda)', 'admin-text', 'site-reviews-actions'),
            // 'lt' => esc_html_x('Lithuanian (Lietuvių kalba)', 'admin-text', 'site-reviews-actions'),
            // 'nb' => esc_html_x('Norwegian (Norsk nynorsk)', 'admin-text', 'site-reviews-actions'),
            // 'pl' => esc_html_x('Polish (Polski)', 'admin-text', 'site-reviews-actions'),
            // 'pt' => esc_html_x('Portuguese (Português)', 'admin-text', 'site-reviews-actions'),
            // 'ro' => esc_html_x('Romanian (Română)', 'admin-text', 'site-reviews-actions'),
            // 'ru' => esc_html_x('Russian (Русский)', 'admin-text', 'site-reviews-actions'),
            // 'sk' => esc_html_x('Slovak (Slovenčina)', 'admin-text', 'site-reviews-actions'),
            // 'sl' => esc_html_x('Slovenian (Slovenščina)', 'admin-text', 'site-reviews-actions'),
            // 'es' => esc_html_x('Spanish (Español)', 'admin-text', 'site-reviews-actions'),
            // 'sv' => esc_html_x('Swedish (Svenska)', 'admin-text', 'site-reviews-actions'),
            // 'tr' => esc_html_x('Turkish (Türkçe)', 'admin-text', 'site-reviews-actions'),
            // 'uk' => esc_html_x('Ukrainian (Українська)', 'admin-text', 'site-reviews-actions'),
        ];
    }
}
