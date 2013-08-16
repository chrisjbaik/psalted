<?php

use Phpmig\Migration\Migration;

class AddRedeemedByToInvites extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `invite` ADD COLUMN redeemed_by INTEGER"
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
