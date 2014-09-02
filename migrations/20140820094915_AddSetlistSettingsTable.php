<?php

use Phpmig\Migration\Migration;

class AddSetlistSettingsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "CREATE TABLE `setlist_settings` (`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, `json` TEXT NOT NULL)",
            "ALTER TABLE `group` ADD COLUMN `settings_id` INTEGER",
            "ALTER TABLE `setlist` ADD COLUMN `settings_id` INTEGER",
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $statements = array(
            "DROP TABLE setlist_settings",
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }
}
