<?php

use Phpmig\Migration\Migration;

class AddInvitesTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "CREATE TABLE IF NOT EXISTS invite (id INTEGER NOT NULL, key TEXT NOT NULL, email TEXT NOT NULL, admin_approved BOOLEAN DEFAULT 0, redeemed BOOLEAN DEFAULT 0, PRIMARY KEY (id))",
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
            "DROP TABLE invite;",
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }
}
