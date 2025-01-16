<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Commands;

use GeminiLabs\SiteReviews\Addon\Actions\Defaults\FieldDefaults;
use GeminiLabs\SiteReviews\Addon\Actions\Defaults\LanguagesDefaults;
use GeminiLabs\SiteReviews\Api;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Response;
use GeminiLabs\SiteReviews\Review;

class DetectLanguage extends AbstractCommand
{
    public Review $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function handle(): void
    {
        $response = $this->makeRequest();
        if (!$response->successful()) {
            return;
        }
        $data = $response->data();
        $isReliable = Arr::getAs('bool', $data, 'detections.0.isReliable');
        $language = $isReliable
            ? strtolower(Arr::get($data, 'detections.0.language'))
            : '';
        $languages = glsr(LanguagesDefaults::class)->defaults();
        if (array_key_exists($language, $languages)) {
            glsr(Database::class)->metaSet($this->review->ID, 'language', $language);
        }
    }

    public function makeRequest(): Response
    {
        $apikey = glsr_get_option('addons.actions.detect_language_api_key');
        $apiUrl = 'https://ws.detectlanguage.com/0.2/';
        $response = glsr(Api::class, ['url' => $apiUrl])->post('detect', [
            'body' => $this->requestBody(),
            'force' => true, // bypass the transient check
            'headers' => [
                'Authorization' => "Bearer {$apikey}",
            ],
        ]);
        if (402 === $response->code) {
            glsr_log()->error('Detect Language API: Quota exceeded. Consider upgrading your plan.');
        } elseif ($response->failed()) {
            $message = Arr::get($response->body, 'error.message', $response->message ?: 'Unknown error');
            glsr_log()->error("Detect Language API: {$message}");
        }
        return $response;
    }

    protected function requestBody(): array
    {
        $text = implode(PHP_EOL, $this->reviewFields());
        return [
            'q' => json_encode($text),
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
