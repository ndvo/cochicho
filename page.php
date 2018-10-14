<?php

namespace Page;

$template = new \stdClass();
$template->html = 'templates/html.php';
$template->header = 'templates/header.php';
$template->navigation = 'templates/navigation.php';
$template->content = 'templates/blank.php';
$template->footer = 'templates/footer.php';
$template->warning = 'templates/warning.php';

function template_render($file, $data){
  if (!file_exists($file)){
    return ;
  }
  ob_start();
  include $file;
  return ob_get_clean();
}

