<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config/db.php');
require_once('users/users.php');
require_once('page.php');

use \DB\Conn as D;

$db = D::get();
$data = new stdClass();
$error = false;

if ( $db == null){
  $data->title = 'Oooops! Something went wrong';
  $error = 'It was not possible to connect to the database';
}

$front_page = function($params, &$data, &$template){
  global $user;
  $data->title = 'Welcome';
  if (empty($user)){
    $data->content = 'Please, log in to the system';
  }
};

$install = function($params, &$data, &$template){
  $db->install();
};

$not_found = function($params, &$data, &$template){
  $template->content = 'templates/content.php';
  $data->title = 'Title not set';
  $data->content = 'nothing to see here!';
};

$login = function($params, &$data, &$template){
  $template->content = 'templates/login.php';
  $data->title = 'Please, provide your credentials!';
};

if (!$error){
  $params = [];
  $routes = [
    '/^admin\/install\/?$/'=> $install,
    '/^\/login\/?$/'=> $login,
    '/^\/?$/'=> $front_page,
    '/.*/' => $not_found,
  ];

  foreach ($routes as $pattern => $function){
    $found = preg_match($pattern, $_SERVER['REQUEST_URI'], $params);
    if ($found == 1){
      $function($params, $data, $template);
      break;
    }
  }
  if (!$found){
    $not_found([], $data, $template);
  }
}
?>

<html>
<head>
   <link rel="stylesheet" type="text/css" href="/css/main.css">
</head>
<body>
  <nav>
    <?php echo \Page\template_render($template->navigation, $data); ?>
  </nav>
  <header>
    <?php echo \Page\template_render($template->header, $data); ?>
  </header>
  <main>
    <?php echo \Page\template_render($template->content, $data); ?>
  </main>
  <footer>
    <?php echo \Page\template_render($template->footer, $data); ?>
  </footer>
</body>
</html>
