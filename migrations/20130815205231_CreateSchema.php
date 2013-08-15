<?php

use Phpmig\Migration\Migration;

class CreateSchema extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $statements = array(
            "CREATE TABLE IF NOT EXISTS song (id INTEGER NOT NULL,  url TEXT,  title TEXT NOT NULL,  chords TEXT,  lyrics TEXT,  artist TEXT,  key TEXT,  copyright TEXT,  spotify_id TEXT,  PRIMARY KEY (id),  UNIQUE (url))",
            "CREATE TABLE IF NOT EXISTS user (  id INTEGER NOT NULL,  email TEXT NOT NULL,  password TEXT NOT NULL,  first_name TEXT NOT NULL,  last_name TEXT NOT NULL,  created_at INTEGER NOT NULL,  PRIMARY KEY (id),  UNIQUE (email));",
            "CREATE TABLE IF NOT EXISTS hybridauth (  id INTEGER NOT NULL,  provider TEXT NOT NULL,  uid INTEGER NOT NULL,  user_id INTEGER NOT NULL,  PRIMARY KEY (id));",
            "CREATE TABLE IF NOT EXISTS group (  id INTEGER NOT NULL,  name TEXT NOT NULL, PRIMARY KEY (id));",
            "CREATE TABLE IF NOT EXISTS group_user (  group_id INTEGER NOT NULL,  user_id INTEGER NOT NULL, user_role INTEGER NOT NULL DEFAULT 0, PRIMARY KEY (group_id, user_id));",
            "CREATE TABLE IF NOT EXISTS setlist (  id INTEGER NOT NULL,  title TEXT,  created_by INTEGER NOT NULL,  created_at INTEGER NOT NULL,  updated_by INTEGER NOT NULL,  updated_at INTEGER NOT NULL,  PRIMARY KEY (id));",
            "CREATE TABLE IF NOT EXISTS tag (  id INTEGER NOT NULL,  name TEXT NOT NULL,  PRIMARY KEY (id));",
            "CREATE TABLE IF NOT EXISTS setlist_tag (  setlist_id INTEGER NOT NULL,  tag_id INTEGER NOT NULL,  PRIMARY KEY (setlist_id, tag_id));",
            "CREATE TABLE IF NOT EXISTS setlist_user (  setlist_id INTEGER NOT NULL,  user_id INTEGER NOT NULL,  PRIMARY KEY (setlist_id, user_id));",
            "CREATE TABLE IF NOT EXISTS group_setlist (  setlist_id INTEGER NOT NULL,  group_id INTEGER NOT NULL,  PRIMARY KEY (setlist_id, group_id));",
            "CREATE TABLE IF NOT EXISTS setlist_song (  setlist_id INTEGER NOT NULL,  song_id INTEGER NOT NULL,  key TEXT, chosen_by INTEGER NOT NULL, priority INTEGER NOT NULL, PRIMARY KEY (setlist_id, song_id));"
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
            "DROP TABLE song;",
            "DROP TABLE user;",
            "DROP TABLE hybridauth;",
            "DROP TABLE group;",
            "DROP TABLE group_user;",
            "DROP TABLE setlist;",
            "DROP TABLE tag;",
            "DROP TABLE setlist_tag;",
            "DROP TABLE setlist_user;",
            "DROP TABLE group_setlist;",
            "DROP TABLE setlist_song;"
        );
        $container = $this->getContainer();
        foreach ($statements as $statement) {
            $container['db']->query($statement);
        }
    }
}
