<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config/db.php');
require_once('users/users.php');
require_once('page.php');

use \DB\Conn as D;
use \User\User as User;

$db = D::get();
$data = new stdClass();
$error = false;

if ( $db == null){
  $data->title = 'Oooops! Something went wrong';
  $error = 'It was not possible to connect to the database';
}

$F_front_page = function($params, &$data, &$template){
  global $user;
  $data->title = 'Welcome';
  $template->content = 'templates/front.php';
  if (empty($user)){
    $data->content = 'Please, log in to the system';
  }
};

$F_install = function($params, &$data, &$template){
  echo "Vamos instalar";
  global $db;
  $db->install();
};

$F_not_found = function($params, &$data, &$template){
  $template->content = 'templates/content.php';
  $data->title = 'Title not set';
  $data->content = 'nothing to see here!';
};

$F_login = function($params, &$data, &$template){
  $template->content = 'templates/login.php';
  $data->title = 'Please, provide your credentials!';
};

$F_register = function($params, &$data, &$template){
  if (empty($_POST)){
    $template->content = 'templates/register.php';
    $data->title = 'Registration';
    $data->user = True;
  }else{
    $u = new User();
    $u->register();
    $data->title = 'Tentativa de criar usuário';
    $template->content = 'templates/welcome.php';
    $data->content = "$u->name ";
  }
};

$F_dbdump = function($params, &$data, &$template){
  $template->content = 'templates/dbdump.php';
};

if (!$error){
  $params = [];
  $routes = [
    '/^\/dbdump\/?$/'=> $F_dbdump,
    '/^\/admin\/install\/?$/'=> $F_install,
    '/^\/login\/?$/'=> $F_login,
    '/^\/register\/?$/'=> $F_register,
    '/^\/?$/'=> $F_front_page,
    '/.*/' => $F_not_found,
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
  <header>
    <?php echo \Page\template_render($template->header, $data); ?>
  </header>
  <nav>
    <?php echo \Page\template_render($template->navigation, $data); ?>
  </nav>
  <main>
    <?php echo \Page\template_render($template->content, $data); ?>
  </main>
  <footer>
    <?php echo \Page\template_render($template->footer, $data); ?>
  </footer>
<script src="js/main.js">
</script>
</body>
</html>
