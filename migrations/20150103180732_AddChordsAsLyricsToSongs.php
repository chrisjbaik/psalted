<?php

use Phpmig\Migration\Migration;

class AddChordsAsLyricsToSongs extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "ALTER TABLE `song` ADD COLUMN `chords_as_lyrics` BOOLEAN NOT NULL DEFAULT 1"
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
