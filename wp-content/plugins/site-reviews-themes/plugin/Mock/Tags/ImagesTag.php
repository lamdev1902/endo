<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Mock\Tags;

use GeminiLabs\SiteReviews\Addon\Themes\Application;

class ImagesTag extends Tag
{
    protected function value(): string
    {
        $image = glsr(Application::class)->url('assets/images/photos/%s.jpg');
        $link = sprintf('<a class="glsr-image" href="javascript:void(0)"><img src="%s" width="640" height="480"/></a>', $image);
        return sprintf('<div class="glsr-review-images">%s</div>',
            sprintf($link, '1').sprintf($link, '2')
        );
    }
}
