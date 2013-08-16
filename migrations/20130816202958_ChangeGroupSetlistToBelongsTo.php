<?php

use Phpmig\Migration\Migration;

class ChangeGroupSetlistToBelongsTo extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `setlist` ADD COLUMN group_id INTEGER NOT NULL DEFAULT 0",
            "DROP TABLE `group_setlist`;"
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
            "CREATE TABLE IF NOT EXISTS group_setlist (  setlist_id INTEGER NOT NULL,  group_id INTEGER NOT NULL,  PRIMARY KEY (setlist_id, group_id));",
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }
}
