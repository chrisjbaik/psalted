CREATE TABLE IF NOT EXISTS song (
  id INTEGER NOT NULL,
  url TEXT,
  title TEXT NOT NULL,
  chords TEXT,
  lyrics TEXT,
  artist TEXT,
  key TEXT,
  copyright TEXT,
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
)