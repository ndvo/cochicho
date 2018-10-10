#!/bin/bash
mkdir html
sqlite3 ../scratch.db .dump > database.sql 
sqlite3 ../scratch.db -header -html 'SELECT * FROM users' |tidy > html/users.html
sqlite3 ../scratch.db -header -html 'SELECT * FROM  messages' > html/messages.html
zip -r db.zip html



