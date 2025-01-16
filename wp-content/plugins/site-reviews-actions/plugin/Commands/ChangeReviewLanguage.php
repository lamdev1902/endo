<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Commands;

use GeminiLabs\SiteReviews\Addon\Actions\Defaults\LanguagesDefaults;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Modules\Notice;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Review;

class ChangeReviewLanguage extends AbstractCommand
{
    public Request $request;
    public Review $review;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->review = glsr_get_review($this->request->post_id);
    }

    public function handle(): void
    {
        if (!glsr()->can('edit_post', $this->review->ID)) {
            $this->fail();
            return;
        }
        glsr(Database::class)->metaSet($this->review->ID, 'language', $this->locale());
        glsr(Notice::class)->addSuccess(
            sprintf(_x('The review language has been changed to %s.', 'admin-text', 'site-reviews-actions'), $this->language())
        );
    }

    public function response(): array
    {
        return [
            'notices' => glsr(Notice::class)->get(),
            'value' => $this->locale(),
        ];
    }

    protected function language(): string
    {
        $languages = glsr(LanguagesDefaults::class)->defaults();
        return $languages[$this->locale()];
    }

    protected function locale(): string
    {
        $languages = glsr(LanguagesDefaults::class)->defaults();
        $locale = $this->request->sanitize('value', 'slug');
        if (!array_key_exists($locale, $languages)) {
            $locale = 'en'; // default to English
        }
        return $locale;
    }
}
