<ul class="<?php foreach ($data->class as $c){ echo "$c ";} ?>">
  <?php foreach ($data->item as $i): ?>
  <li><?php echo $i; ?></li>
  <?php endforeach; ?>
</ul>
