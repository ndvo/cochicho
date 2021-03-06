CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL UNIQUE,
  mail TEXT NOT NULL UNIQUE,
  password TEXT ,
  pubkey TEXT UNIQUE,
  privkey TEXT UNIQUE,
  iv TEXT,
  terms BOOLEAN 
  )
;

CREATE TABLE IF NOT EXISTS messages(
  id INTEGER PRIMARY KEY,
  content TEXT NOT NULL,
  recipient INTEGER NOT NULL references users(id),
  sender    INTEGER NOT NULL references users(id),
  ekeys TEXT
  )
;

CREATE TABLE IF NOT EXISTS sessions(
    uid INTEGER UNIQUE,
    cookie BLOB NOT NULL 
    )
;

CREATE TABLE IF NOT EXISTS recovery(
  user TEXT NOT NULL references users(name),
  secret TEXT NOT NULL,
  generated INTEGER NOT NULL,
  used BOOLEAN
  )
;
