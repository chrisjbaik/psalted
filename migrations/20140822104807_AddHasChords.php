<?php

use Phpmig\Migration\Migration;

class AddHasChords extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `song` ADD COLUMN `has_chords` BOOLEAN NOT NULL DEFAULT 0",
            "UPDATE `song` SET `has_chords` = haschords(chords)",
        );
        $container = $this->getContainer();
        $container['db']->sqliteCreateFunction('haschords', function($chords) {
            $result = preg_match('/\[[A-G]/', $chords);
            return $result;
        });
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
