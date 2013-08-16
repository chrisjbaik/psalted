<?php

use Phpmig\Migration\Migration;

class AddSongFtsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "CREATE VIRTUAL TABLE song_fts USING fts3(title, lyrics, artist)",
            "INSERT INTO song_fts(rowid, title, lyrics, artist) SELECT id, title, lyrics, artist FROM song"
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
            "DROP TABLE song_fts;"
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }
}
