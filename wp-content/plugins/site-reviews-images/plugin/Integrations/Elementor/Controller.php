<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Integrations\Elementor;

use GeminiLabs\SiteReviews\Controllers\AbstractController;

class Controller extends AbstractController
{
    /**
     * @param \Elementor\Widgets_Manager $manager
     * @action elementor/widgets/register
     */
    public function registerElementorWidgets($manager): void
    {
        $manager->register(new ElementorImagesWidget());
    }
}
