<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications;

use GeminiLabs\SiteReviews\Addon\Notifications\Defaults\NotificationDefaults;
use GeminiLabs\SiteReviews\Addon\Notifications\Defaults\SettingsDefaults;
use GeminiLabs\SiteReviews\Arguments;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Cast;
use GeminiLabs\SiteReviews\Helpers\Str;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\TemplateTags;
use GeminiLabs\SiteReviews\Modules\Sanitizer;
use GeminiLabs\SiteReviews\Review;

/**
 * @property string $condition
 * @property array $conditions
 * @property bool $enabled
 * @property string $heading
 * @property string $message
 * @property array $recipients
 * @property int $schedule
 * @property string $subject
 * @property string $uid
 */
class Notification extends Arguments
{
    /** @var Review */
    public $review;

    /** @var array */
    protected $notification;

    /** @var \GeminiLabs\SiteReviews\Arguments */
    protected $settings;

    public function __construct($values, Review $review)
    {
        $this->notification = glsr(NotificationDefaults::class)->restrict($values);
        $this->review = $review->refresh();
        $this->settings = glsr(Application::class)->options(SettingsDefaults::class);
        parent::__construct($this->notification);
        $this->parseConditions();
    }

    public function interval(): int
    {
        return !empty($this->get('schedule'))
            ? $this->get('schedule') * DAY_IN_SECONDS
            : 0;
    }

    public function isEnabled(): bool
    {
        return $this->get('enabled');
    }

    public function isValid(array $data = []): bool
    {
        if (empty($this->get('uid')) || empty($this->get('condition'))) {
            return false;
        }
        if ('always' === $this->get('condition')) {
            return true;
        }
        $passed = 0;
        foreach ($this->get('conditions') as $condition) {
            $passed += Cast::toInt($condition->isValid($data));
        }
        if ('any' === $this->get('condition')) {
            $result = $passed > 0;
        } else {
            $result = $passed === count($this->get('conditions'));
        }
        return glsr(Application::class)->filterBool('notification/is-valid', $result, $this, $data);
    }

    public function original(): array
    {
        return $this->notification;
    }

    public function send(): bool
    {
        $templateTags = $this->interpolateTags();
        $data = [
            'args' => [],
            'review' => $this->review,
        ];
        $email = [
            'to' => $this->recipients(),
            'from' => $this->fromEmail(),
            'reply-to' => $this->replyToEmail(),
            'style' => 'default',
            'subject' => $templateTags['subject'],
            'template' => 'default',
            'template-tags' => $templateTags,
        ];
        try {
            return (bool) glsr(Email::class)->compose($email, $data)->send();
        } catch (\Exception $error) {
            return false;
        }
    }

    protected function fromEmail(): string
    {
        $email = glsr(Template::class)->interpolate($this->settings->from_email, 'notification/from_email', [
            'context' => glsr(TemplateTags::class)->tags($this->review, [
                'include' => ['admin_email'],
            ]),
        ]);
        if (!empty($email) && !empty($this->settings->from_name)) {
            $name = glsr(Template::class)->interpolate($this->settings->from_name, 'notification/from_name', [
                'context' => glsr(TemplateTags::class)->tags($this->review, [
                    'include' => ['site_title'],
                ]),
            ]);
            $email = sprintf('%s <%s>', $name, $email);
        }
        return $email;
    }

    protected function interpolateTags(): array
    {
        $footer = glsr(Template::class)->interpolate($this->settings->footer_text, 'notification/footer', [
            'context' => glsr(TemplateTags::class)->tags($this->review, [
                'include' => ['review_ip', 'review_link', 'site_title', 'site_url'],
            ]),
        ]);
        $subject = glsr(Template::class)->interpolate($this->get('subject'), 'notification/subject', [
            'context' => glsr(TemplateTags::class)->tags($this->review, [
                'exclude' => ['admin_email', 'verify_url'],
            ]),
        ]);
        $heading = glsr(Template::class)->interpolate($this->get('heading'), 'notification/heading', [
            'context' => glsr(TemplateTags::class)->tags($this->review, [
                'exclude' => ['admin_email', 'verify_url'],
            ]),
        ]);
        $message = glsr(Template::class)->interpolate($this->get('message'), 'notification/message', [
            'context' => glsr(TemplateTags::class)->tags($this->review, [
                'exclude' => ['admin_email'],
            ]),
        ]);
        $message = do_shortcode($message);
        $message = glsr(Sanitizer::class)->sanitizeTextPost($message);
        $message = wp_kses_stripslashes($message); // ensure <a> links work
        $message = wptexturize($message);
        $message = wpautop($message);
        $message = str_replace('&lt;&gt; ', '', $message);
        $message = str_replace(']]>', ']]&gt;', $message);
        $image = glsr(Builder::class)->img(['alt' => '', 'src' => $this->settings->header_image]);
        return compact('footer', 'heading', 'image', 'message', 'subject');
    }

    protected function parseConditions(): void
    {
        $conditions = explode('|', Arr::get($this->notification, 'conditions'));
        $parsedCondition = Str::restrictTo(['all', 'always', 'any'], array_shift($conditions));
        $parsedConditions = [];
        foreach ($conditions as $values) {
            $parts = explode(':', $values);
            if (3 !== count($parts)) {
                continue;
            }
            $values = array_combine(['field', 'operator', 'value'], $parts);
            $condition = new Condition($values, $this);
            glsr(Application::class)->action('condition', $condition, $this);
            $parsedConditions[] = $condition;
        }
        $this->set('condition', $parsedCondition);
        $this->set('conditions', $parsedConditions);
    }

    protected function recipients(): array
    {
        $results = [];
        foreach ($this->get('recipients') as $recipient) {
            if ('admin' === $recipient) {
                $results[] = get_bloginfo('admin_email');
                continue;
            }
            if ('assigned_post_author' === $recipient) {
                $posts = $this->review->assignedPosts();
                $emails = $this->userEmails(wp_list_pluck($posts, 'post_author'));
                $results = array_merge($results, $emails);
                continue;
            }
            if ('assigned_user' === $recipient) {
                $emails = $this->userEmails($this->review->assigned_users);
                $results = array_merge($results, $emails);
                continue;
            }
            if ('reviewer' === $recipient) {
                $results[] = $this->review->email;
                continue;
            }
            $results[] = $recipient;
        }
        $results = glsr(Application::class)->filterArray('notification/recipients', $results, $this);
        $results = array_map([glsr(Sanitizer::class), 'sanitizeEmail'], $results);
        return Arr::reindex(Arr::unique($results));
    }

    protected function replyToEmail(): string
    {
        return glsr(Template::class)->interpolate($this->settings->reply_to_email, 'notification/reply_to_email', [
            'context' => glsr(TemplateTags::class)->tags($this->review, [
                'include' => ['admin_email'],
            ]),
        ]);
    }

    protected function userEmails(array $userIds): array
    {
        if (empty($userIds)) {
            return [];
        }
        $users = get_users(['fields' => ['user_email'], 'include' => $userIds]);
        return wp_list_pluck($users, 'user_email');
    }
}
