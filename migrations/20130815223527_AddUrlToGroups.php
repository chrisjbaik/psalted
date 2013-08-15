<?php

use Phpmig\Migration\Migration;

class AddUrlToGroups extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `group` ADD COLUMN url TEXT"
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
    {}
}
