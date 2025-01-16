<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Tags;

use GeminiLabs\SiteReviews\Addon\Themes\ThemeSettings;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Modules\Avatar;
use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewAvatarTag;

class AvatarTag extends ReviewAvatarTag
{
    /** @var int */
    public $avatarSize;

    public function regenerateAvatar(string $avatarUrl): string
    {
        if ($this->canRegenerateAvatar()) {
            return glsr(Avatar::class)->generate($this->review, $this->avatarSize);
        }
        return $avatarUrl;
    }

    /**
     * We ignore the avatar settings here
     */
    protected function handle(): string
    {
        $this->setAvatarSize();
        $this->review->set('avatar', $this->regenerateAvatar($this->value()));
        return $this->wrap(
            glsr(Avatar::class)->img($this->review, $this->avatarSize)
        );
    }

    protected function setAvatarSize(): void
    {
        $this->avatarSize = glsr(ThemeSettings::class)
            ->themeId($this->args->cast('theme', 'int'))
            ->get('design.avatar.avatar_size', 0);
    }
}
