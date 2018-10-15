<?php if (!empty($data->header)) : ?>
<section class="panel header">
    <ul >
      <?php foreach($data->header as $item): ?>
      <li> <?php echo $item; ?></li>
      <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>

<?php 
  $sent = [];
  $received = [];
  foreach ($data->messages as $m){
    if ($m->to == $data->user->name){
      $received[] = $m;
    }
    if($m->from == $data->user->name){
      $sent[] = $m;
    }
  }
?>


<section class="actions section-switch">
  <button id="received" class="active" onclick="chooseOne(this, ['section#received','section#sent'], ['button#sent']);" >Received</button>
  <button id="sent" onclick="chooseOne(this, ['section#sent','section#received'], ['button#received'])" >Sent</button>
  <a href="/compose"><button >New message</button></a>
</section>

<section id="received" class="panel messages received">
  <h2>Received Messages</h2>

  <?php if (!empty($received)): foreach ($received as $m):  ?>

    <article class="message">

      <section class="envelope"> 
        <div class="from">
          From: <span class="from">
          <a href="/conversations?friend=<?php echo urlencode($m->from); ?>">
            <?php echo $m->from; ?>
          </a>
        </div>
        <div class="datetime">
          on: <?php echo date('l jS \of F Y h:i:s A', $m->time) ; ?>
        </div>
        <div class="actions">
          <form method="post" action="/compose">
            <input type="hidden" name="action" value="reply">
            <input type="hidden" name="msg" value="<?php echo $m->mid; ?>">
            <input type="hidden" name="to" value="<?php echo $m->from ;?> ">
            <button type="submit" name="action" value="reply">Reply</button>
          </form>
          <form method="post">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="msg" value="<?php echo $m->mid; ?>">
            <button type="submit" name="action" value="delete" title="Deleting: be careful, this action cannot be undone.">Delete</button>
          </form>
        </div>
      </section>

      <section class="title">
        <h4 class="message title">
           <?php echo $m->title ; ?>
        </h4>
      </section>

      <section class="content">
        <?php echo $m->body ; ?>
      </section>

    </article>
  <?php endforeach; else: ?>
  <article>
    <h3>No messages yet...</h3>
      <p>It seems that none of your friends have sent you a message yet.</p>
      <p>You can <a href="/compose">Compose a message to send</a> or <a href="/invite">invite your friends to join</a></p>
  </article>
<?php endif; ?>
</section>

<section id="sent" class="panel messages sent" style="display: none">

  <h2>Sent Messages</h2>
  <?php if (!empty($sent)): foreach($sent as $m): ?>

    <article class="message">
      <section class="envelope"> 
        <div class="from">
          To: <span class="to">
          <a href="/conversations?friend=<?php echo urlencode($m->to); ?>">
            <?php echo $m->to; ?>
          </a>
        </div>
        <div class="datetime">
          on: <?php echo date('l jS \of F Y h:i:s A', $m->time) ; ?>
        </div>
      </section>

      <section class="title">
        <h4 class="message title">
           <?php echo $m->title ; ?>
        </h4>
      </section>

      <section class="content">
        <?php echo $m->body ; ?>
      </section>

    </article>

  <?php  endforeach; else: ?>
  <article>
    <h3>No messages yet...</h3>
      <p>It seems that haven't send any messages yet.</p>
      <p>You can <a href="/compose">Compose a message to send</a> or <a href="/invite">invite your friends to join</a></p>
  </article>
<?php endif ; ?>
</section>


</section>
