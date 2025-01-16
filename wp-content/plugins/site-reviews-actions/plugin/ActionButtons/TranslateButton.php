<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\ActionButtons;

use GeminiLabs\Nitotm\Eld\LanguageDetector;
use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Addon\Actions\Commands\DetectLanguage;
use GeminiLabs\SiteReviews\Addon\Actions\Defaults\LanguagesDefaults;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Helpers\Cast;

class TranslateButton extends ButtonAbstract
{
    public function iconPath(): string
    {
        return glsr(Application::class)->path('assets/images/icons/translate.svg');
    }

    public function text(): string
    {
        return __('Translate to English', 'site-reviews-actions');
    }

    protected function detectLanguage(): string
    {
        if (!empty(glsr(Application::class)->option('detect_language_api_key'))) {
            $command = new DetectLanguage($this->review);
            $command->handle();
            $language = glsr(Database::class)->meta($this->review->ID, 'language');
            return Cast::toString($language);
        }
        wp_raise_memory_limit(); // just in case...
        $eld = new LanguageDetector();
        $eld->cleanText(true);
        $result = $eld->detect($this->review->content);
        $language = $result->isReliable()
            ? $result->language
            : '';
        $languages = glsr(LanguagesDefaults::class)->defaults();
        if (!array_key_exists($language, $languages)) {
            $language = '';
        }
        return $language;
    }

    protected function isEnglishReview(): bool
    {
        if (!$this->review->isValid()) {
            return true; // Not a real review
        }
        if (!metadata_exists('post', $this->review->ID, '_language')) {
            $language = $this->detectLanguage();
            glsr(Database::class)->metaSet($this->review->ID, 'language', $language);
        }
        return 'en' === glsr(Database::class)->meta($this->review->ID, 'language');
    }

    protected function isRestricted(): bool
    {
        if (empty(glsr(Application::class)->option('deepl_api_key'))) {
            return true;
        }
        if ($this->isEnglishReview()) {
            return true;
        }
        return false;
    }
}
