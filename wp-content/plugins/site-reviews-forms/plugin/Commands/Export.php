<?php

namespace GeminiLabs\SiteReviews\Addon\Forms\Commands;

use GeminiLabs\SiteReviews\Addon\Forms\Application;
use GeminiLabs\SiteReviews\Addon\Forms\FormFields;
use GeminiLabs\SiteReviews\Addon\Forms\ReviewTemplate;
use GeminiLabs\SiteReviews\Commands\AbstractCommand;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Request;

class Export extends AbstractCommand
{
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(): void
    {
        $charset = get_bloginfo('charset');
        $mimeType = sanitize_mime_type('application/json');
        $filename = sanitize_file_name($this->filename());
        nocache_headers();
        header("Content-Disposition: attachment; filename={$filename}");
        header("Content-Type: {$mimeType}; charset={$charset}");
        $data = wp_json_encode($this->data());
        echo html_entity_decode($data);
        exit;
    }

    public function data(): array
    {
        $items = $this->queryData($this->request->post_ids);
        array_walk($items, function (&$item) {
            $fields = maybe_unserialize($item->fields);
            $item->fields = $fields;
        });
        return [ // order is intentional
            'created' => gmdate('Y-m-d H:i'),
            'generator' => Application::NAME,
            'version' => glsr(Application::class)->version,
            'items' => $items,
        ];
    }

    public function filename(): string
    {
        $postIds = $this->request->post_ids;
        $title = 1 === count($postIds)
            ? get_the_title($postIds[0])
            : get_bloginfo('name');
        $title = strtolower($title);
        $filename = sprintf('%s.%s.json', $title, Application::ID);
        $filename = sanitize_file_name($filename);
        return $filename;
    }

    public function queryData(array $postIds = []): array
    {
        if (empty($postIds)) {
            return [];
        }
        $postIds = implode(',', $postIds);
        $sql = glsr(Query::class)->sql("
            SELECT 
                p.post_title AS title,
                p.post_name AS slug,
                p.post_status AS status,
                pm1.meta_value AS fields,
                pm2.meta_value AS review_template
            FROM table|posts p
            INNER JOIN table|postmeta pm1 ON (pm1.post_id = p.ID)
            INNER JOIN table|postmeta pm2 ON (pm2.post_id = p.ID)
            WHERE p.post_type = %s
                AND pm1.meta_key = %s
                AND pm2.meta_key = %s
                AND p.ID IN ({$postIds})
        ",
            Application::POST_TYPE,
            FormFields::META_KEY,
            ReviewTemplate::META_KEY
        );
        $results = glsr(Database::class)->dbGetResults($sql);
        return $results;
    }
}
