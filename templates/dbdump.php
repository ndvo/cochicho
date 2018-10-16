<?php $r = random_int(0,10000); ?>
<p>Here you can downlad a dump of our database.</p>
<p>You have four options: </p>
<ol>
  <li><a href="/content/dbdump/database.sql?no-cache=<?php echo $r; ?>">SQL: a SQL dump of the database. You may need to import it into your database</a></li>
  <li><a title="A html version of the database" href="/content/dbdump/dump.html?no-cache=<?php echo $r; ?>">Html tables</a> Check out the database contents without the need to download anything.</li>
  <li><a href="/content/dbdump/content.db?no-cache=<?php echo $r; ?>">SQLITE: a SQLITE binary database.</a> You may read it with any Sqlite browres, such as https://sqliteonline.com/ or https://sqlitebrowser.org/</li>

</ol>
