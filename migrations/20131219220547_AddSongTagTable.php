<?php

use Phpmig\Migration\Migration;

class AddSongTagTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "CREATE TABLE IF NOT EXISTS song_tag (song_id INTEGER NOT NULL, tag_id TEXT NOT NULL, added_by INTEGER NOT NULL, PRIMARY KEY (song_id, tag_id))",
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
            "DROP TABLE song_tag;",
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }
}
