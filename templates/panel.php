<?php if (!empty($data->header)) : ?>
<section class="panel header">
    <ul >
      <?php foreach($data->header as $item): ?>
      <li> <?php echo $item; ?></li>
      <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>

<?php if (!empty($data->messages)): ?>
<section class="panel messages">

  <?php foreach ($data->messages as $m):  ?>
    <article class="message">

      <section class="envelope"> 
        <div class="from">
          From: <span class="from">
          <a href="/conversations?friend=<?php echo url_encode($m->from); ?>">
            <?php echo $m->from; ?>
          </a>
        </div>
        <div class="datetime">
          on: <?php echo date('l jS \of F Y h:i:s A') ; ?>
        </div>
        <div class="actions">
          <form method="post">
            <input type="hidden" name="action" value="reply">
            <input type="hidden" name="msg" value="<?php echo $m->mid; ?>">
            <button type="submit">Reply</button>
          </form>
          <form method="post">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="msg" value="<?php echo $m->mid; ?>">
            <button type="submit">Delete</button>
          </form>
          <form method="post">
            <input type="hidden" name="action" value="unread">
            <input type="hidden" name="msg" value="<?php echo $m->mid; ?>">
            <button type="submit">Mark as unread</button>
          </form>
        </div>
      </section>

      <section class="title">
        <h4 class="message title">
           <?php echo $m->title ; ?>
        </h4>
      </section>
      
      <section class="content">
        <?php echo $m->content ; ?>
      </section>

    </article>
  <?php endforeach; ?>

  <?php else: ?>

  <article>
  <h3>No messages yet...</h3>
  <p>It seems that none of your friends have sent you a message yet.</p>
  <p>You can <a href="/compose">Compose a message to send</a> or <a href="/invite">invite your friends to join</a></p>
  </article

</section>
<?php endif; ?>
