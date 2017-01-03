<?php

use Phpmig\Migration\Migration;

class AddAccessKeyToSetlist extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `setlist` ADD COLUMN `access_key` TEXT"
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
