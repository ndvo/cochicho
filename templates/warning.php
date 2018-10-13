<?php if (!empty($data->warning->title)): ?>
<h3><?php echo $data->warning->title ; ?></h3>
<?php endif; ?>
<?php 
if (is_object($data->warning) and !empty($data->warning->content )){
  echo $data->warning->content;
}elseif(is_string($data->warning)){
  echo $data->warning;
}
?>



