
<article class="message">
  <h2><?php echo $data->message->title; ?></h2>
  <section class="envelope">
      <div class="from">From: <?php echo $data->message->from ; ?></div>
      <div class="to">To: <?php echo $data->message->to ; ?></div>
      <div class="time">When: <?php echo date('l jS \of F Y h:i:s A', $data->message->time) ; ?></div>
  </section>
<section class="body">
<?php echo $data->message->body ; ?>
</section>
</article>
