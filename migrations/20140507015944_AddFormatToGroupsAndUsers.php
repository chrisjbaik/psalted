<?php

use Phpmig\Migration\Migration;

class AddFormatToGroupsAndUsers extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `group` ADD COLUMN format TEXT",
            "ALTER TABLE `user` ADD COLUMN format TEXT"
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
