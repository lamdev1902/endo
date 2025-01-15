<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Defaults;

use GeminiLabs\SiteReviews\Addon\Actions\Application;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Defaults\DefaultsAbstract;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Sanitizer;

class ActionLogDefaults extends DefaultsAbstract
{
    /**
     * The values that should be cast before sanitization is run.
     * This is done before $sanitize and $enums.
     */
    public array $casts = [
        'ID' => 'int',
        'rating_id' => 'int',
        'user_id' => 'int',
    ];

    /**
     * The values that should be sanitized.
     * This is done after $casts and before $enums.
     */
    public array $sanitize = [
        'action' => 'slug',
        'data' => '',
        'date' => 'date',
        'ID' => 'min:0',
        'ip_address' => 'text',
        'rating_id' => 'min:0',
        'user_id' => 'min:0',
    ];

    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    protected function defaults(): array
    {
        return [
            'action' => '',
            'data' => '', // unserialized when finalized
            'date' => '',
            'ID' => 0,
            'ip_address' => '',
            'rating_id' => 0,
            'user_id' => 0,
        ];
    }

    /**
     * Finalize provided values, this always runs last.
     */
    protected function finalize(array $values = []): array
    {
        $data = maybe_unserialize($values['data']);
        if ($user = get_user_by('ID', $values['user_id'])) {
            $href = esc_url(get_edit_profile_url($user->ID));
            $text = glsr(Sanitizer::class)->sanitizeUserName($user->display_name);
            $data['user_url'] = glsr(Builder::class)->a([
                'href' => $href,
                'role' => 'button',
                'text' => $text,
            ]);
        } elseif (!empty($data['email'])) {
            $data['user_url'] = glsr(Builder::class)->a([
                'href' => "mailto:{$data['email']}",
                'role' => 'button',
                'text' => $data['email'],
            ]);
        }
        $values['data'] = glsr()->args($data);
        return $values;
    }
}
