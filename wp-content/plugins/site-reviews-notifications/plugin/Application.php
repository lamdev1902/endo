<?php

namespace GeminiLabs\SiteReviews\Addon\Notifications;

use GeminiLabs\SiteReviews\Addon\Notifications\Defaults\NotificationDefaults;
use GeminiLabs\SiteReviews\Addons\Addon;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Helpers\Str;

final class Application extends Addon
{
    public const ID = 'site-reviews-notifications';
    public const LICENSED = true;
    public const NAME = 'Review Notifications';
    public const SLUG = 'notifications';

    public function notifications(): array
    {
        $notifications = Arr::consolidate(get_option(Str::snakeCase(static::ID)));
        array_walk($notifications, function (&$notification) {
            $notification = glsr(NotificationDefaults::class)->restrict($notification);
        });
        return $notifications;
    }
}
