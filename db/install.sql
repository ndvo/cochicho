CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY,
  name TEXT NOT NULL,
  mail TEXT NOT NULL,
  password TEXT ,
  pubkey TEXT,
  privkey TEXT,
  terms BOOLEAN 
);

CREATE TABLE IF NOT EXISTS messages(
  id INTEGER PRIMARY KEY,
  content TEXT,
  recipient INTEGER NOT NULL references users(id),
  sender    INTEGER NOT NULL references users(id)
)
