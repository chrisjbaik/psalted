CREATE TABLE song (
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