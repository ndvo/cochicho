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
}else{
  $user = new stdClass();
}

$F_front_page = function($params, &$data, &$template){
  global $user;
  if (empty($user->authenticated)){
    $data->title = 'Welcome';
    $template->content = 'templates/front.php';
  }else{
    $data->title = 'Overview';
    $template->content = 'templates/panel.php';
  }
};



$F_install = function($params, &$data, &$template){
  global $db;
  $data->title = "Installation and upgrade";
  $template->content = 'templates/install.php';
  $results = $db->install();
  if (!empty($results)){
    $warning = new stdClass();
    $warning->title = 'Errors occured during installation';

  }
  $data->content = "The installation process was completed. A backup of the old database was mada. Any older backup was destroyed. Your new database backup is within the same folder, with a .bup extension.";

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
      $data->warning = new stdClass();
      $data->warning->title = "Login sucessful";
      $data->warning->content = "<p>Welcome $user->name.</p>";
      $template->content = "";
    }
  }
};

$F_register = function($params, &$data, &$template){
  if (empty($_POST)){
    $template->content = 'templates/register.php';
    $data->title = 'Registration';
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

$F_compose = function ($params, &$data, &$template){
  global $user;
  $data->from = $user->name;
  $template->content = 'templates/compose.php';
};

$F_logout = function ($params, &$data, &$template){
  global $user;
  $user->unauthenticate();
};



if (!$error){
  $params = [];
  $routes = [
    '/^\/logout\/?$/'=> $F_logout,
    '/^\/dbdump\/?$/'=> $F_dbdump,
    '/^\/admin\/install\/?$/'=> $F_install,
    '/^\/compose\/?$/'=>$F_compose,
    '/^\/login\/?$/'=> $F_login,
    '/^\/register\/?$/'=> $F_register,
    '/^(\/panel)?\/?$/'=> $F_front_page,
    '/.*/' => $F_not_found,
  ];
  $data->user = $user;
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
  <?php if (!empty($data->warning)): ?>
  <dialog class="warning" open >
    <?php echo  \Page\template_render($template->warning, $data);; ?>
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
