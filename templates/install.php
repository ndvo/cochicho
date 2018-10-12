<h1><?php echo $data->title; ?></h1>

<?php echo $data->content; ?>

<h2>Further instructions:</h2>

<p>Make sure the HTMLPurifier chache folder is writable: </p>


<h2>Requirements</h2>

<h3>Apache2</h3>

<h3>PHP 7</h3>

<h3>Third party libraries</h3>

<pre><code>
  sudo chown www-data htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer
</pre></code>

