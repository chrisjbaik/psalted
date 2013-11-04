<?php

use Phpmig\Migration\Migration;

class AddSongIdToSongFts extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "DROP TABLE `song_fts`",
            "CREATE VIRTUAL TABLE song_fts USING fts3(title, lyrics, artist)",
            "INSERT INTO song_fts(docid, title, lyrics, artist) SELECT id, title, lyrics, artist FROM song",
            "CREATE TRIGGER song_bu BEFORE UPDATE ON song BEGIN DELETE FROM song_fts WHERE docid=old.rowid; END;",
            "CREATE TRIGGER song_bd BEFORE DELETE ON song BEGIN DELETE FROM song_fts WHERE docid=old.rowid; END;",
            "CREATE TRIGGER song_au AFTER UPDATE ON song BEGIN INSERT INTO song_fts(docid, title, lyrics, artist) VALUES(new.rowid, new.title, new.lyrics, new.artist); END;",
            "CREATE TRIGGER song_ai AFTER INSERT ON song BEGIN INSERT INTO song_fts(docid, title, lyrics, artist) VALUES(new.rowid, new.title, new.lyrics, new.artist); END;"
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
            "DROP TABLE song_fts;",
            "DROP TRIGGER song_bu",
            "DROP TRIGGER song_bd",
            "DROP TRIGGER song_au",
            "DROP TRIGGER song_ai"
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }
}
