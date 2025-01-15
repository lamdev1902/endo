<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Tags;

use GeminiLabs\SiteReviews\Addon\Images\Application;
use GeminiLabs\SiteReviews\Helper;
use GeminiLabs\SiteReviews\Modules\Html\Builder;
use GeminiLabs\SiteReviews\Modules\Html\Tags\ReviewTag;

class ReviewImagesTag extends ReviewTag
{
    public function wrap(string $value, string $wrapWith = null): string
    {
        $rawValue = $value;
        $value = glsr()->filterString($this->for.'/value/'.$this->tag, $value, $this);
        if (Helper::isNotEmpty($value)) {
            $value = glsr()->filterString($this->for.'/wrapped', $value, $rawValue, $this);
            $classes = [sprintf('glsr-%s-%s', $this->for, $this->tag)];
            if ('lightbox' === glsr(Application::class)->imageModal()) {
                $classes[] = 'spotlight-group';
            }
            $value = glsr(Builder::class)->div([
                'class' => implode(' ', $classes),
                'text' => $value,
            ]);
        }
        return glsr()->filterString($this->for.'/wrap/'.$this->tag, $value, $rawValue, $this);
    }

    protected function handle(): string
    {
        if ($this->isHidden()) {
            return '';
        }
        glsr()->store('use_images', true); // this allows us to load the lightbox script
        return $this->wrap($this->value());
    }

    protected function value(): string
    {
        $attachments = $this->review->images();
        if (empty($attachments)) {
            return '';
        }
        return glsr(Application::class)->build('views/images', [
            'attachments' => $attachments,
            'modal' => glsr(Application::class)->imageModal(),
        ]);
    }
}
