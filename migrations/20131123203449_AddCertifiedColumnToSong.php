<?php

use Phpmig\Migration\Migration;

class AddCertifiedColumnToSong extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE 'song' ADD COLUMN certified BOOLEAN NOT NULL DEFAULT 0"
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
