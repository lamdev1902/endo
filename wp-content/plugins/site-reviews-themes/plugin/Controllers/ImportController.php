<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Controllers;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Commands\Import;
use GeminiLabs\SiteReviews\Addons\Controller as AddonController;
use GeminiLabs\SiteReviews\Contracts\PluginContract;
use GeminiLabs\SiteReviews\Helpers\Arr;
use GeminiLabs\SiteReviews\Modules\Notice;
use GeminiLabs\SiteReviews\Request;

class ImportController extends AddonController
{
    public function app(): PluginContract
    {
        return glsr(Application::class);
    }

    /**
     * @filter site-reviews/page-header/buttons
     */
    public function filterPageHeaderButtons(array $buttons): array
    {
        if (Application::POST_TYPE !== glsr_current_screen()->post_type) {
            return $buttons;
        }
        if (!glsr()->can('import')) {
            return $buttons;
        }
        $buttons = Arr::insertBefore('new', $buttons, [
            'import' => [
                'class' => 'components-button is-secondary',
                'href' => admin_url("admin.php?import={$this->app()->post_type}"),
                'text' => _x('Import', 'admin-text', 'site-reviews-themes'),
            ],
        ]);
        return $buttons;
    }

    /**
     * We are running the importer here instead of using the "load-importer-{$importer}" hook
     * because the Router hooks provide mutex protection while the importer hook does not.
     * @action site-reviews/route/admin/import-{Application::POST_TYPE}
     */
    public function onImport(Request $request): void
    {
        if (!glsr()->can('import')) {
            glsr(Notice::class)->addError(
                _x('You do not have permission to import files.', 'admin-text', 'site-reviews-themes')
            );
            return;
        }
        $this->execute(new Import($request));
    }

    /**
     * @action admin_init
     */
    public function registerImporter(): void
    {
        register_importer(
            Application::POST_TYPE,
            glsr()->name.': '.Application::NAME,
            _x('Import review themes from a JSON export file.', 'admin-text', 'site-reviews-themes'),
            [$this, 'renderImporterCallback']
        );
    }

    /**
     * @callback $this->registerImporter()
     */
    public function renderImporterCallback(): void
    {
        $this->app()->render('views/import', [
            'app' => $this->app(),
            'max_size_bytes' => (string) wp_max_upload_size(),
            'notices' => glsr(Notice::class)->get(),
        ]);
    }
}
