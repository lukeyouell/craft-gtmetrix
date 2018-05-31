<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\stripecheckout\migrations;

use lukeyouell\stripecheckout\Support;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;
use craft\helpers\MigrationHelper;

class Install extends Migration
{
    // Public Properties
    // =========================================================================

    public $driver;

    // Public Methods
    // =========================================================================

    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->dropForeignKeys();
        $this->dropTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    protected function createTables()
    {
        $tablesCreated = false;

        // support_tickets table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%support_tickets}}');
        if ($tableSchema === null) {
            $tablesCreated = true;

            $this->createTable(
                '{{%checkout_charges}}',
                [
                    'id'          => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid'         => $this->uid(),
                    // Custom columns in the table
                    'testId'      => $this->string(),
                    'state'      => $this->string(),
                    'entryId'     => $this->integer()->notNull(),
                    'loadTime'    => $this->integer(),
                    'pagespeed'   => $this->integer(),
                    'yslow'       => $this->integer(),
                    'data'        => $this->text(),
                ]
            );
        }

        return $tablesCreated;
    }

    protected function addForeignKeys()
    {
        $this->addForeignKey(null, '{{%checkout_charges}}', ['id'], '{{%elements}}', ['id'], 'CASCADE');
    }

    protected function dropForeignKeys()
    {
        MigrationHelper::dropAllForeignKeysOnTable('{{%checkout_charges}}', $this);
    }

    protected function dropTables()
    {
        $this->dropTable('{{%checkout_charges}}');
    }
}
