<h1><?php echo $data->title; ?></h1>

<p><?php echo $data->content; ?></p>

<p>Exchange messages with your friends without worrying about who else might be
reading it</p>

<p>Every single message is encrypted and only then stored, so that no even our
team could possibly read it.</p>

<p><strong>How does it work?</strong> When you register we hash your password
and save the hash so that you can login next time. Then, we hash is again with
a different algorithm, but we don't save this version. We use it to create a
Key Pair. As we never stored neither your password, nor the secret hash
generated from it, no one, not even in the possession of both our code and our
database could read your messages.</p>

<p><strong>Key pair?</strong> Yup. Imagine your have a mailbox with two keys:
the first one opens a slot where people can put letters in, but cannot take
them out. This is the <em>public</em> key. The other key is used to take the
letters out. This is the <em>private</em> key. We use this scheme so that we
can encrypt messages without us needing to know the private keys.</p>
