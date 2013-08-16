<?php

use Phpmig\Migration\Migration;

class AddRoleColumnForUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `user` ADD COLUMN roles TEXT DEFAULT 'a:1:{i:0;s:6:\"member\";}'"
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
