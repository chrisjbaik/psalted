<?php

use Phpmig\Migration\Migration;

class AlterSetlistSettingsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "DROP TABLE setlist_settings",
            "CREATE TABLE `setlist_settings` (`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, `copies` TEXT NOT NULL, `pagenumber` TEXT NOT NULL, `size` TEXT NOT NULL, `songnumber` TEXT NOT NULL, `style` TEXT NOT NULL)",
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

    }
}
