<?php

use Phpmig\Migration\Migration;

class AddUserIdToSetlist extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `setlist` ADD COLUMN user_id INTEGER NOT NULL DEFAULT 0;",
            "DROP TABLE setlist_user;"
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
            "CREATE TABLE IF NOT EXISTS setlist_user (  setlist_id INTEGER NOT NULL,  user_id INTEGER NOT NULL,  PRIMARY KEY (setlist_id, user_id));"
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }
}
