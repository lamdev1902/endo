<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Commands;

use GeminiLabs\SiteReviews\Addon\Actions\Defaults\FieldDefaults;
use GeminiLabs\SiteReviews\Api;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Response;
use GeminiLabs\SiteReviews\Review;

class TranslateReview extends AbstractCommand
{
    public bool $isTranslated;
    public string $message = '';
    public Request $request;
    public Review $review;
    public array $translatable;
    public array $translation = [];

    public function __construct(Request $request)
    {
        $this->isTranslated = $request->cast('translated', 'bool');
        $this->request = $request;
        $this->review = glsr_get_review($request->review_id);
        $this->translatable = $this->reviewFields();
    }

    public function handle(): void
    {
        $translation = $this->translatable;
        if (!$this->isTranslated) {
            $translation = $this->review->meta()->cast('_en', 'array');
        }
        $merged = array_merge($translation, $this->translatable);
        $modifed = array_diff_key($merged, array_intersect_key($translation, $this->translatable));
        if (empty($modifed)) {
            $this->translation = $translation;
            return;
        }
        $response = $this->makeRequest();
        if ($response->successful()) {
            $body = $response->body;
            $language = strtolower(Arr::get($body, 'translations.1.detected_source_language'));
            $text = wp_list_pluck(Arr::get($body, 'translations'), 'text');
            $translation = array_combine(array_keys($this->translatable), $text);
            glsr(Database::class)->metaSet($this->review->ID, 'en', $translation);
            glsr(Database::class)->metaSet($this->review->ID, 'language', $language);
        }
        $this->translation = $translation;
    }

    public function makeRequest(): Response
    {
        $apikey = glsr_get_option('addons.actions.deepl_api_key');
        $apiUrl = str_ends_with($apikey, ':fx')
            ? 'https://api-free.deepl.com/v2/' // free account
            : 'https://api.deepl.com/v2/'; // pro account
        $response = glsr(Api::class, ['url' => $apiUrl])->post('translate', [
            'body' => wp_json_encode($this->requestBody()), // send as json
            'force' => true, // bypass the transient check
            'headers' => [
                'Authorization' => "DeepL-Auth-Key {$apikey}",
                'Content-Type' => 'application/json',
            ],
        ]);
        if ($response->failed()) {
            $this->message = __('The translation service could not be reached. Please wait a few minutes and then try again.', 'site-reviews-actions');
        }
        if (500 <= $response->code) {
            glsr_log()->error('DeepL API: Temporary errors in the DeepL service.');
        } elseif (456 === $response->code) {
            glsr_log()->error('DeepL API: Quota exceeded. Consider upgrading your subscription.');
        } elseif (429 === $response->code) {
            glsr_log()->error('DeepL API: Too many requests.');
        } elseif ($response->failed()) {
            $message = $response->message ?: 'Unknown error';
            glsr_log()->error("DeepL API: {$message}");
        }
        return $response;
    }

    public function response(): array
    {
        $custom = array_diff_key($this->translation, array_flip(['title', 'content']));
        $values = array_diff_key($this->translation, $custom);
        $this->review->merge($values);
        $this->review->offsetSet('custom', $custom);
        $text = $this->isTranslated
            ? __('Translate to English', 'site-reviews-actions')
            : __('View Original', 'site-reviews-actions');
        return [
            'rendered' => (string) $this->review->build($this->request->toArray()), // the rendered translated review
            'message' => $this->message,
            'text' => $text,
            'translated' => !$this->isTranslated,
        ];
    }

    protected function requestBody(): array
    {
        return [
            'target_lang' => 'EN', // @todo
            'text' => array_values($this->translatable),
        ];
    }

    protected function reviewFields(): array
    {
        $fields = [
            'title' => $this->review->title,
            'content' => $this->review->content,
        ];
        $custom = glsr('Addon\Forms\FormFields')->customFields((int) $this->review->form);
        $custom = Arr::consolidate($custom);
        $custom = array_map([glsr(FieldDefaults::class), 'restrict'], $custom);
        $custom = wp_list_pluck($custom, 'translatable', 'name');
        $custom = array_filter($custom);
        foreach ($custom as $key => $value) {
            $fields[$key] = $this->review->custom()->get($key, '');
        }
        return $fields;
    }
}
