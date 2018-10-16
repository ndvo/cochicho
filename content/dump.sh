#!/bin/bash
BASEDIR=$(dirname "$0")
mkdir "$BASEDIR"/dbdump/html
sqlite3  "$BASEDIR"/content.db .dump >    "$BASEDIR"/dbdump/database.sql 
temp="$BASEDIR"/dbdump/temp.html
db="$BASEDIR"/content.db 

echo "<style>" >> $temp
cat $BASEDIR/style.css >> $temp
echo "</style>" >> $temp
createtable(){
  echo  "<h2>"$1" table</h2>" >> $temp
  echo "<table>" >> $temp
  sqlite3  $db -header -html 'SELECT * FROM '$1' ' >> $temp;
  echo "</table>" >> $temp
}


createtable users
createtable messages
createtable sessions
createtable recovery

cat $temp |tidy > "$BASEDIR"/dbdump/dump.html
rm $temp

cp   "$BASEDIR"/../content.db  "$BASEDIR"/dbdump/content.db

       


