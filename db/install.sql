CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL UNIQUE,
  mail TEXT NOT NULL UNIQUE,
  password TEXT ,
  pubkey TEXT UNIQUE,
  privkey TEXT UNIQUE,
  iv TEXT,
  terms BOOLEAN 
);

CREATE TABLE IF NOT EXISTS messages(
  id INTEGER PRIMARY KEY,
  content TEXT,
  recipient INTEGER NOT NULL references users(id),
  sender    INTEGER NOT NULL references users(id)
);

CREATE TABLE IF NOT EXISTS sessions(
    cookie BLOB UNIQUE,
    uid INTEGER)
;
