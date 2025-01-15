<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications;

use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Review;

class Notifications
{
    /** @var array */
    public $notifications;

    /** @var Review */
    public $review;

    public function __construct(Review $review)
    {
        $this->notifications = glsr(Application::class)->notifications();
        $this->review = $review;
    }

    public function notification(string $uid): ?Notification
    {
        if ($notification = Arr::searchByKey($uid, $this->notifications(), 'uid')) {
            return $notification;
        }
        return null;
    }

    public function notifications(): array
    {
        $notifications = [];
        foreach ($this->notifications as $notification) {
            $notification = new Notification($notification, $this->review);
            glsr(Application::class)->action('notification', $notification, $this);
            $notifications[] = $notification;
        }
        return $notifications;
    }

    public function queue(array $data = []): void
    {
        foreach ($this->notifications() as $notification) {
            if (!$notification->isEnabled()) {
                continue;
            }
            if (!$notification->isValid($data)) {
                continue;
            }
            glsr(Queue::class)->once(time() + $notification->interval(), 'queue/notification', wp_parse_args($data, [
                'notification_uid' => $notification->uid,
                'review_id' => $this->review->ID,
            ]));
        }
    }

    public function send(string $uid): bool
    {
        if ($notification = $this->notification($uid)) {
            return $notification->send();
        }
        return false;
    }
}
