<?php

namespace GeminiLabs\SiteReviews\Addon\Actions\Database;

use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Database\Query;
use GeminiLabs\SiteReviews\Database\Tables\AbstractTable;

class TableActionsLog extends AbstractTable
{
    public string $name = 'actions_log';

    public function addForeignConstraints(): void
    {
        $this->addForeignConstraint('rating_id', $this->table('ratings'), 'ID');
    }

    public function dropForeignConstraints(): void
    {
        $this->dropForeignConstraint('rating_id', $this->table('ratings'));
    }

    public function removeInvalidRows(): void
    {
        glsr(Database::class)->dbSafeQuery(
            glsr(Query::class)->sql("
                DELETE t
                FROM {$this->tablename} AS t
                LEFT JOIN table|ratings AS r ON (r.ID = t.rating_id)
                WHERE r.ID IS NULL
            ")
        );
    }

    /**
     * WordPress codex says there must be two spaces between PRIMARY KEY and the key definition.
     * @see https://codex.wordpress.org/Creating_Tables_with_Plugins
     */
    public function structure(): string
    {
        return glsr(Query::class)->sql("
            CREATE TABLE {$this->tablename} (
                ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                rating_id bigint(20) unsigned NOT NULL,
                user_id bigint(20) unsigned NOT NULL default '0',
                ip_address varchar(255) NOT NULL,
                action varchar(20) NOT NULL,
                data longtext,
                date datetime NOT NULL default '0000-00-00 00:00:00',
                PRIMARY KEY  (ID),
                KEY glsr_actions_rating_id_index (rating_id),
                KEY glsr_actions_user_id_index (user_id)
            ) ENGINE=InnoDB {$this->db->get_charset_collate()};
        ");
    }
}
