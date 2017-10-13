<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\gtmetrix\migrations;

use lukeyouell\gtmetrix\GTmetrix;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
        }

        return true;
    }

   /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%gtmetrix_tests}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%gtmetrix_tests}}',
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                    'testId' => $this->string(),
                    'entryId' => $this->integer(),
                    'entryTitle' => $this->string(),
                    'entryUrl' => $this->string(),
                    'location' => $this->integer(),
                    'browser' => $this->integer(),
                    'connection' => $this->string(),
                    'state' => $this->string()->notNull()->defaultValue('requested'),
                    'response' => $this->text(),
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists('{{%gtmetrix_tests}}');
    }
}
