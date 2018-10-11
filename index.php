<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config/db.php');
require_once('users/users.php');
require_once('page.php');


session_start(
  [
    'cookie_httponly'=>true,
    'cookie_domain'=>'security',
  ]
);
        

use \DB\Conn as D;
use \User\User as User;

$db = D::get();
$data = new stdClass();
$error = false;

if ( $db == null){
  $data->title = 'Oooops! Something went wrong';
  $error = 'It was not possible to connect to the database';
}
if ($_SERVER['REQUEST_URI'] != '/admin/install'){
  $user = new User();
}

$F_front_page = function($params, &$data, &$template){
  global $user;
  $data->title = 'Welcome';
  $template->content = 'templates/front.php';
  if (empty($user->id)){
    $data->content = 'Please, log in to the system';
  }
};

$F_install = function($params, &$data, &$template){
  global $db;
  $data->title = "Instalation and upgrade";
  $data->content = "Installing a new system's version";
  $template->content = 'templates/install.php';
  $db->install();

};

$F_not_found = function($params, &$data, &$template){
  $template->content = 'templates/content.php';
  $data->title = 'Title not set';
  $data->content = 'nothing to see here!';
};

$F_login = function($params, &$data, &$template){
  if (empty($_POST)){
    $template->content = 'templates/login.php';
    $data->title = 'Please, provide your credentials!';
  }else{
    global $user;
    $ok = $user->authenticate();
    if (!$ok){
      $err = "<ul>\n";
      foreach ($user->err as $e){
        $err.= "<li>$e</li>";
      }
      $err.= "</ul>";
      $data->warning = $err;
      $data->title = "";
      $template->content = 'templates/login.php';
      $data->title = 'Something went wrong. Please, review your credentials!';
    }else{
      $data->warning = "Welcome $user->name";
    }
  }
};

$F_register = function($params, &$data, &$template){
  if (empty($_POST)){
    $template->content = 'templates/register.php';
    $data->title = 'Registration';
    $data->user = True;
  }else{
    global $user;
    $ok = $user->register();
    if ($ok){
      $data->title = "Thank you for registering, $user->name";
      $template->content = 'templates/welcome.php';
    }else{
      $err = "<ul>\n";
      foreach ($user->err as $e){
        $err.= "<li>$e</li>";
      }
      $err.= "</ul>";
      $data->warning = $err;
    }
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
  <?php echo empty($warning)? '': $warning; ?>
  <header>
    <?php echo \Page\template_render($template->header, $data); ?>
  </header>
  <nav>
    <?php echo \Page\template_render($template->navigation, $data); ?>
  </nav>
  <?php if (!empty($data->warning)): ?>
  <dialog open >
    <?php echo  $data->warning ; ?>
      <span class="close-button" onclick="this.parentNode.removeAttribute('open');this.parentNode.addAttribute('close');">
        &times;
      </span>
  </dialog>
  <?php endif; ?>
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
