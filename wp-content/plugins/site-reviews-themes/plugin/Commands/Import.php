<?php

namespace GeminiLabs\SiteReviews\Addon\Themes\Commands;

use GeminiLabs\SiteReviews\Addon\Themes\Application;
use GeminiLabs\SiteReviews\Addon\Themes\Defaults\ImportDefaults;
use GeminiLabs\SiteReviews\Addon\Themes\Defaults\ImportItemDefaults;
use GeminiLabs\SiteReviews\Addon\Themes\ThemeBuilder;
use GeminiLabs\SiteReviews\Addon\Themes\ThemeSettings;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Exceptions\FileException;
use GeminiLabs\SiteReviews\Exceptions\FileNotFoundException;
use GeminiLabs\SiteReviews\Modules\Notice;
use GeminiLabs\SiteReviews\Request;
use GeminiLabs\SiteReviews\Upload;
use GeminiLabs\SiteReviews\UploadedFile;

class Import extends AbstractCommand
{
    use Upload;

    public int $imported;
    public int $skipped;
    public int $updated;

    protected array $errors;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->errors = [];
        $this->imported = 0;
        $this->request = $request;
        $this->skipped = 0;
        $this->updated = 0;
    }

    public function handle(): void
    {
        $this->fail();
        if (!$file = $this->getImportFile('application/json')) {
            return;
        }
        if (!$data = $this->getImportFileData($file)) {
            return;
        }
        if (!$data = $this->verifiedData($data)) {
            return;
        }
        $this->import($data);
        $this->displayResults();
    }

    public function response(): array
    {
        return [ // order is intentional
           'imported' => $this->imported,
           'updated' => $this->updated,
           'skipped' => $this->skipped,
        ];
    }

    protected function displayResults(): void
    {
        $response = array_filter($this->response());
        if (empty($response)) {
            glsr(Notice::class)->addWarning(
                _x('No themes were found to import.', 'admin-text', 'site-reviews-themes')
            );
            return;
        }
        $this->pass();
        $messages = [
            'imported' => sprintf(_x('%d imported', 'admin-text', 'site-reviews-themes'), $this->imported),
            'skipped' => sprintf(_x('%d skipped', 'admin-text', 'site-reviews-themes'), $this->skipped),
            'updated' => sprintf(_x('%d updated', 'admin-text', 'site-reviews-themes'), $this->updated),
        ];
        array_walk($response, fn (&$val, $key) => ($val = $messages[$key]));
        $total = array_sum($response);
        $message = sprintf('<strong>%s:</strong> %s.',
            sprintf(_nx('%s theme was found', '%s themes were found', $total, 'admin-text', 'site-reviews-themes'), $total),
            implode('; ', $response)
        );
        if (!array_key_exists('skipped', $response)) {
            glsr(Notice::class)->addSuccess($message);
            return;
        }
        glsr(Notice::class)->addWarning($message, $this->errors);
    }

    protected function getPostId(string $slug): int
    {
        $sql = "
            SELECT ID FROM table|posts
            WHERE post_name = %s AND post_type = %s
        ";
        return (int) glsr(Database::class)->dbGetVar(
            glsr(Query::class)->sql($sql, $slug, Application::POST_TYPE)
        );
    }

    protected function getPostValues(array $item): array
    {
        $themeBuilder = $item['theme_builder']; // @todo normalize this!
        $themeSettings = $item['theme_settings'];  // @todo normalize this!
        return [
            'meta_input' => [
                '_form' => $item['form'],
                glsr(ThemeBuilder::class)->metaKey() => $themeBuilder,
                glsr(ThemeSettings::class)->metaKey() => $themeSettings,
            ],
            'post_status' => $item['status'],
            'post_type' => Application::POST_TYPE,
            'post_title' => $item['title'],
        ];
    }

    protected function import(array $data): void
    {
        foreach ($data['items'] as $item) {
            $item = glsr(ImportItemDefaults::class)->restrict($item);
            $postId = $this->getPostId($item['slug']);
            if ('ignore' === $this->request->duplicate_action || empty($postId)) {
                $this->insertRecord($item);
                continue;
            }
            if ('replace' === $this->request->duplicate_action) {
                $this->updateRecord($postId, $item);
                continue;
            }
            $this->skipRecord(_x('Duplicate themes were skipped.', 'admin-text', 'site-reviews-themes')); // The duplicate action is "skip"
        }
    }

    protected function insertRecord(array $item): void
    {
        $args = $this->getPostValues($item);
        $result = wp_insert_post($args, true);
        if (is_wp_error($result)) {
            $this->skipRecord($result->get_error_message());
            return;
        }
        ++$this->imported;
    }

    protected function skipRecord(string $error = ''): void
    {
        if (!empty($error)) {
            $this->errors[] = $error;
            $this->errors = array_unique($this->errors);
        }
        ++$this->skipped;
    }

    protected function updateRecord(int $postId, array $item): void
    {
        $args = $this->getPostValues($item);
        $result = wp_update_post(wp_parse_args($args, ['ID' => $postId]), true);
        if (is_wp_error($result)) {
            $this->skipRecord($result->get_error_message());
            return;
        }
        ++$this->updated;
    }

    protected function verifiedData(array $data): array
    {
        $data = glsr(ImportDefaults::class)->restrict($data);
        if (Application::NAME !== $data['generator']) {
            glsr(Notice::class)->addError(
                sprintf(_x('The file you uploaded is not a %s import file.', 'Review Themes (admin-text)', 'site-reviews-themes'), Application::NAME)
            );
            return [];
        }
        if (version_compare(glsr(Application::ID)->version, $data['version'], '<')) {
            glsr(Notice::class)->addError(
                sprintf(_x('The file you uploaded requires a newer version of %s.', 'Review Themes (admin-text)', 'site-reviews-themes'), Application::NAME)
            );
            return [];
        }
        return $data;
    }
}
