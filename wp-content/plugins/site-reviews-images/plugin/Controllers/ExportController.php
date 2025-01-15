<?php

namespace GeminiLabs\SiteReviews\Addon\Images\Controllers;

use GeminiLabs\SiteReviews\Controllers\AbstractController;
use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Tables;

class ExportController extends AbstractController
{
    /**
     * @filter site-reviews/database/sql/export-with-ids
     * @filter site-reviews/database/sql/export-with-slugs
     */
    public function filterDatabaseSql(string $statement): string
    {
        $postType = glsr()->post_type;
        $search = [
            "r.terms,",
            "INNER JOIN table|posts AS p ON (p.ID = r.review_id)",
        ];
        $replace = [
            "r.terms, GROUP_CONCAT(DISTINCT img.guid SEPARATOR '|') AS images,",
            "INNER JOIN table|posts AS p ON (p.ID = r.review_id) LEFT JOIN table|posts AS img ON (img.post_parent = p.ID AND img.post_type = 'attachment')",
        ];
        $statement = str_replace($search, $replace, $statement);
        return $statement;
    }
}
