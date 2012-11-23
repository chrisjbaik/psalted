CREATE TABLE song (
	id INTEGER NOT NULL,
	url TEXT,
	title TEXT,
	chords TEXT,
	lyrics TEXT,
	PRIMARY KEY (id),
	UNIQUE (url)
);