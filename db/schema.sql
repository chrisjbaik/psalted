CREATE TABLE IF NOT EXISTS song (
  id INTEGER NOT NULL,
  url TEXT,
  title TEXT NOT NULL,
  chords TEXT,
  lyrics TEXT,
  artist TEXT,
  key TEXT,
  copyright TEXT,
  spotify_id TEXT,
  PRIMARY KEY (id),
  UNIQUE (url)
);

CREATE TABLE IF NOT EXISTS user (
  id INTEGER NOT NULL,
  email TEXT NOT NULL,
  password TEXT NOT NULL,
  first_name TEXT NOT NULL,
  last_name TEXT NOT NULL,
  created_at INTEGER NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (email)
);

CREATE TABLE IF NOT EXISTS playlist (
  id INTEGER NOT NULL,
  title TEXT,
  created_by INTEGER NOT NULL,
  created_at INTEGER NOT NULL,
  updated_by INTEGER NOT NULL,
  updated_at INTEGER NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS playlist_user (
  playlist_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  PRIMARY KEY (playlist_id, user_id)
);

CREATE TABLE IF NOT EXISTS playlist_song (
  playlist_id INTEGER NOT NULL,
  song_id INTEGER NOT NULL,
  PRIMARY KEY (playlist_id, song_id)
);