<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config/settings.php');
require_once('config/db.php');
require_once('users/users.php');
require_once('messages/messages.php');
require_once('page.php');


session_start(
  [
    'cookie_httponly'=>true,
    'cookie_domain'=>DOMAIN,
  ]
);
        

use \DB\Conn as D;
use \User\User as User;
use \Message\Message as Message;

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
  global $db;
  if (empty($user->authenticated)){
    $data->title = 'Welcome';
    $template->content = 'templates/front.php';
  }else{
    $data->user = $user;
    $data->title = 'Overview';
    $template->content = 'templates/panel.php';
    $messages = $db->retrieve_user_messages($user->id);
    $m = [];
    foreach ($messages as $me){
      $m[] = new Message($encrypted=True, $me);
    }
    $data->messages = $m;
    $action = empty($_POST['action'])?'':$_POST['action'];
    if($action == 'delete'){
      $mid = $_POST['msg']? intval($_POST['msg']): false;
      if ($mid){
        $m= $db->retrieve_message($mid);
        if (!empty($m)){
          $tobe_deleted = new Message($encrypted=True, $me);
          $attempt = $tobe_deleted->remove_from_database();
          if ($attempt==1){
            $data->warning = "Message $tobe_deleted->title was deleted.<br>It won't be shown in new requests.";
          }else{
            $data->warning = "Couldn't remove message.";
          }
        }
      }
    }
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
  $template->content = 'templates/login.php';
  global $user;
  if (empty($_POST)){
    $template->content = 'templates/login.php';
    if ($user->authenticated){
      $data->title = "Welcome $user->name";
    }else{
      $data->title = 'Please, provide your credentials!';
    }
  }else{
    $ok = $user->authenticate();
    $data->user = $user;
    $data->title = "My profile";
    if (!$ok){
      $err = "<ul>\n";
      foreach ($user->err as $e){
        $err.= "<li>$e</li>";
      }
      $err.= "</ul>";
      $data->warning = $err;
      $data->title = "";
      $data->title = 'Something went wrong. Please, review your credentials!';
    }else{
      $data->warning = new stdClass();
      $data->warning->title = "Login sucessful";
      $data->warning->content = "<p>Welcome $user->name.</p>";
    }
  }
};

$F_register = function($params, &$data, &$template){
  $template->content = 'templates/register.php';
  $data->title = 'Registration';
  if (!empty($_POST)){
    global $user;
    $ok = $user->register();
    if ($ok){
      $data->title = "Thank you for registering, $user->name";
      $data->content = "Please, log in to exchange messages with your friends.";
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
  exec(dirname(__FILE__).'/content/dump.sh');
  $template->content = 'templates/dbdump.php';
};

$F_compose = function ($params, &$data, &$template){
  global $user;
  global $db;
  $usernames = $db->all_usernames();
  $data->ulist = array_column($usernames, 'name');
  $data->from = $user->name;
  $template->content = 'templates/compose.php';
  $action = empty($_POST['action'])?'':$_POST['action'];
  $post2data = [
    'to' => 'to',
    'title'=>'title',
    'body'=>'body'
  ];
  $_POST['from'] = $user->name;
  foreach ($post2data as $k=>$v){
    if (!empty($_POST[$k]) and empty($data->$v)){
      $data->$v = trim($_POST[$k]);
    }
  }
  if ($action == 'send'){
    if (!in_array($_POST['to'], $data->ulist)){
      $data->warning = "The user to whom you are trying to send a message is not registered.";
    }else{
      $envelope = Message::envelope_from_post(time()); 
      $m = new Message($encrypted=False, $envelope=$envelope);
      $ok = $m->store_message();
      if ($ok){
        $_SESSION['message'] = "Your message has been sent.";
        $template->content = 'templates/message.php';
        header("Location: /panel");
        die();
      }else{
        $data->warning = "Something has gone wrong";
      }
    }
  }
};

$F_logout = function ($params, &$data, &$template){
  global $user;
  $user->unauthenticate();
  $template->content = "templates/logout.php";
  $data->warning = "You were successfully logged out";
};

$F_about = function ($params, &$data, &$template){
  $template->content = "templates/about.php";
};

$F_password_reset = function ($params, &$data, &$template){
  global $db;
  if (!empty($_POST['username'])){
    $alleged = trim($_POST['username']);
    $data->account = True;
    $mail = $db->mail_by_name($alleged)['mail'];
    if (empty($_POST['secret'])){
      if (!empty($mail)){
        $secret = trim(base64_encode(random_bytes(16)));
        $db->insert_recovery($alleged, $secret);
        $to = $mail;
        $subject = 'Account recovery';
        $message = "Hi
          Here is your secret key to recover your account at ".DOMAIN."
          Did you ask for a pasword recovery?

          Secret: $secret

          Messaging Privately security team.";
        $headers = 'From: ndvo@security.ndvo.geekgalaxy.com' . "\r\n" .
            'Reply-To: ndvo@security.ndvo.geekgalaxy.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $ok =mail($to, $subject, $message, $headers);
      }
    }else{
      $secret = trim($_POST['secret']);
      $ok = $db->use_secret($alleged, $secret);
      if ($ok){
        #TODO: go to change password form
      }else{
        $data->warning = "Sorry, you were not able to provide the secret.";
      }
    }
  }
  $template->content = "templates/passwordreset.php";
};


if (!$error){
  $params = [];
  $routes = [
    '/^\/logout\/?$/'=> $F_logout,
    '/^\/about\/?$/'=> $F_about,
    '/^\/dbdump\/?$/'=> $F_dbdump,
    '/^\/admin\/install\/?$/'=> $F_install,
    '/^\/compose\/?$/'=>$F_compose,
    '/^\/(login|profile)\/?$/'=> $F_login,
    '/^\/register\/?$/'=> $F_register,
    '/^(\/panel|\/index\.html)?\/?$/'=> $F_front_page,
    '/^\/password-reset\/?$/'=> $F_password_reset,
    '/.*/' => $F_not_found,
  ];
  $data->user = $user;
  foreach ($routes as $pattern => $function){
    $found = preg_match($pattern, $_SERVER['REQUEST_URI'], $params);
    if ($found == 1){
      $function($params, $data, $template);
      if (!empty($_SESSION['message'])){
        $data->warning = $_SESSION['message'];
        unset($_SESSION['message']);
      }
      break;
    }
  }
  if (!$found){
    $not_found([], $data, $template);
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
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
