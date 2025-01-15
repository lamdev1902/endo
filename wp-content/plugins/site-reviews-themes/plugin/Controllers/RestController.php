<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Controllers;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Mock\MockReviewOld;
use GeminiLabs\SiteReviews\Addon\Themes\Theme;
use GeminiLabs\SiteReviews\Helpers\Arr;
use WP_REST_Server as Server;

class RestController extends \WP_REST_Posts_Controller
{
    public function __construct($postType)
    {
        parent::__construct($postType);
        $this->namespace = glsr()->id.'/v1';
    }

    /**
     * @return void
     */
    public function register_routes()
    {
        register_rest_route(Application::ID.'/v1', '/settings', [
            'callback' => [$this, 'getSettings'],
            'methods' => Server::READABLE,
            'permission_callback' => [$this, 'getPermissionCheck'],
        ]);
        register_rest_route(Application::ID.'/v1', '/tags', [[
            'callback' => [$this, 'getTags'],
            'methods' => Server::READABLE,
            'permission_callback' => [$this, 'getPermissionCheck'],
        ]]);
        register_rest_route(Application::ID.'/v1', '/tags/(?P<form_id>\d+)', [[
            'args' => [
                'form_id' => [
                    'validate_callback' => function ($param) { return is_numeric($param); },
                ],
            ],
            'callback' => [$this, 'getTags'],
            'methods' => Server::READABLE,
            'permission_callback' => [$this, 'getPermissionCheck'],
        ]]);
        register_rest_route(Application::ID.'/v1', '/templates', [
            'callback' => [$this, 'getTemplates'],
            'methods' => Server::READABLE,
            'permission_callback' => [$this, 'getPermissionCheck'],
        ]);
        parent::register_routes();
    }

    /**
     * @return bool|\WP_Error
     */
    public function getPermissionCheck()
    {
        if (!is_user_logged_in()) {
            return new \WP_Error('rest_authentication', _x('You must be logged in to access this route.', 'admin-text', 'site-reviews-themes'), [
                'status' => rest_authorization_required_code(),
            ]);
        }
        return true;
    }

    /**
     * @return \WP_REST_Response|\WP_Error
     */
    public function getSettings()
    {
        $config = Arr::unflatten(glsr(Application::class)->config('theme-settings'));
        $theme = Arr::consolidate(get_post_meta(get_the_ID(), '_theme', true));
        $data = [];
        foreach ($config as $group => $settings) {
            $data[$group] = [];
            foreach ($settings as $name => $values) {
                $values['group'] = $group;
                $values['name'] = $name;
                $values['value'] = Arr::get($theme, $group.'.'.$name, $values['default']);
                $data[$group][] = $values;
            }
        }
        return rest_ensure_response($data);
    }

    /**
     * @return \WP_REST_Response|\WP_Error
     */
    public function getTags(\WP_REST_Request $request)
    {
        $formId = $request['form_id'] ?? 0;
        $fields = (new Theme($formId))->tags();
        return rest_ensure_response($fields);
    }

    /**
     * @return \WP_REST_Response|\WP_Error
     */
    public function getTemplates()
    {
        return rest_ensure_response([
            'templates' => $this->templates(),
            'rating_images' => $this->ratingImages(),
        ]);
    }

    protected function ratingImages(): array
    {
        return glsr(Theme::class)->stars();
    }

    protected function templates(): array
    {
        $templates = [];
        $dir = glsr(Application::class)->path('views/templates');
        if (is_dir($dir)) {
            $iterator = new \DirectoryIterator($dir);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isFile() && 'php' === $fileinfo->getExtension()) {
                    $slug = $fileinfo->getBasename('.php');
                    ob_start();
                    include $fileinfo->getPathname();
                    $templates[$slug] = ob_get_clean();
                }
            }
        }
        $templates = glsr(Application::class)->filterArray('rest/templates', $templates);
        natsort($templates);
        foreach ($templates as $slug => $content) {
            $templates[$slug] = (string) (new MockReviewOld($content));
        }
        return $templates;
    }
}
