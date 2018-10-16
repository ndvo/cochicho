<?php

namespace User;

require_once('config/db.php');

use \DB\Conn as D;


class User{
  public $id;
  public $name;
  public $mail;
  public $public_key;
  public $authenticated;
  private $iv;
  private $method = "AES-256-CBC";
  private $encrypted_key;
  public $err;
  private $db;
  private $password;
  public $accepted_terms;

  static function is_valid_name($name){
    if (preg_match("/[\d\w+@-][\d\w\.+ '@-]+[\d\w+@-]/", $name)){
      return $name;
    }
    return false;
  }

  public function __construct($id = false, $name = false){
    $this->err = [];
    $this->db = D::get();
    if (empty($id) and empty($name)){
      $logged_in = $this->am_i_in();
    }else{
      if (!empty($id)){
        $this->load_by_id($id);
      }elseif(!empty($name)){
        $this->load_by_name($name);
      }
    }
  }

  public function register(){
    $err = [];
    if (!empty($_POST)){
      $uname = trim($_POST['username']);
      if ( !self::is_valid_name($uname) ){
        $err[] = 'The username chosen is invalid.';
      } 
      if (empty($this->generate_pwd())){
        $err[] = 'Please, provide the 3 passwords';
      }
      if (!$this->password_requirements($this->generate_pwd())){
        $err[] = 'Please, provide a stronger password';
      }
      global $db;
      $exists = $db->mail_by_name($uname);
      if (!empty($exists)){
        $err[]='Sorry. This username was already taken';
      }
      $this->mail = $_POST['mail'];
      $umailconfirm = $_POST['mail2'];
      $pwd = password_hash($this->generate_pwd(), PASSWORD_DEFAULT);
      if ($this->mail != $umailconfirm){
        $err[] = 'Please, be sure to input the same address in both email and confirm email fields.';
      }
      if (!filter_var($this->mail, FILTER_VALIDATE_EMAIL)){
        $err[] = 'There is something wrong with the provided email.';
      }
      if (empty($_POST['terms'])){
        $err[] = 'The use of this service is conditioned to the acceptance of the Terms of Use.';
      }
      if (empty($err)){
        $this->name = $uname;
        $this->create_secret($this->generate_pwd());
        $this->iv = random_bytes(16);
        $ok = $this->create_key_pair();
        if ($ok){
          $this->db->insert_user(
            $this->mail, $this->name, $pwd, $this->public_key, $this->encrypted_key, $this->iv, 1
          );
          return True;
        }
      }
    }
    $this->err = $err;
    return False;
  }

  public function recover(){
    $err = [];
    if (!empty($_POST)){
      $uname = $_SESSION['uname'];
      if (empty($this->generate_pwd())){
        $err[] = 'Please, provide the 3 passwords';
      }
      if (!$this->password_requirements($this->generate_pwd())){
        $err[] = 'Please, provide a stronger password';
      }
      global $db;
      $this->mail = $db->mail_by_name($uname)['mail'];
      $pwd = password_hash($this->generate_pwd(), PASSWORD_DEFAULT);
      if (empty($err)){
        $this->name = $uname;
        $this->create_secret($this->generate_pwd());
        $this->iv = random_bytes(16);
        $ok = $this->create_key_pair();
        if ($ok){
          $recovered = $this->db->recover_user(
             $this->name, $pwd, $this->public_key, $this->encrypted_key, $this->iv
          );
          if ($recovered){
            $this->unauthenticate();
            return True;
          }else{
            return False;
          }
        }
      }
    }
    $this->err = $err;
    return False;
  }

  private function password_requirements($p){
    // 8 chars, one non word and not only digits
    $requirements = [
      '/\D/',
      '/[^A-Za-z]/',
      '/[A-Za-z]/',
      '/.{8}/'
    ];
    foreach ($requirements as $r){
      if (!preg_match($r, $p)){
        return False;
      }
    }
    return True;
  }

  private function create_secret($pwd=False){
      if ($pwd){
        $this->secret = hash('sha256', $pwd);
        $_SESSION['secret'] = $this->secret;
      }elseif(empty($this->secret) and !empty($_SESSION['secret'])){
        $this->secret = $_SESSION['secret'];
      }
  }


  private function log_in($name){
    if (self::is_valid_name($name)){
      $u = $this->db->basic_user_by_name($name);
      if (!empty($u)){
        $this->name = $u['name'];
        $this->password = $u['password'];
        $this->id = $u['id'];
        return True;
      }
    }
    $this->name = "Anonymous";
    return False;
  }

  private function am_i_in(){
    if (empty($_COOKIE['wai'])){
      setcookie('wai', random_bytes(256), time()+1*60*60*24, '/',DOMAIN, $secure=false, $httponly=true  );
      return False;
    }else{
      $uid = $this->db->get_session($_COOKIE['wai']);
      if (empty($uid)){
        return False;
      }else{
        $uid = $uid['uid'];
        $u = $this->db->full_user_by_id($uid);
        $this->name = $u['name'];
        $this->id = $u['id'];
        $this->public_key = $u['pubkey'];
        $this->encrypted_key = $u['privkey'];
        $this->mail = $u['mail'];
        $this->accepted_terms = $u['terms'];
        $this->create_secret();
        $this->authenticated = true;
        $this->iv = $u['iv'];
        return True;
      }
    }
  }

  private function load_by_id($id){
    $id = intval($id);
    $u = $this->db->full_user_by_id($id);
    if (!empty($u['name'])){
      $this->name = $u['name'];
      $this->id = $u['id'];
      $this->public_key = $u['pubkey'];
      $this->mail = $u['mail'];
      $this->accepted_terms = $u['terms'];
      $this->create_secret();
      $this->authenticated = true;
      return  true;
    }else{
      return false;
    }
  }

  private function load_by_name($name){
    if (self::is_valid_name($name)){
      $u = $this->db->full_user_by_name($name);
      if (!empty($u['name'])){
        $this->name = $u['name'];
        $this->id = $u['id'];
        $this->public_key = $u['pubkey'];
        $this->mail = $u['mail'];
        $this->accepted_terms = $u['terms'];
        return True;
      }
    }
    return False;
  }

  public function save(){
    $this->$id = hash('sha256', $this->name);
     
  }

  public function load_profile(){
    if ($this->authenticate){
        
    }
  }

  public function check_message_signature($message){
  }

  public function encrypt($message){
  }


  private function sign_message($message){
  }

  public function send_message($message, $dest){
    $sealed = "";
    $env_keys = [];
    openssl_seal( $message, $sealed_data , $env_keys , [$this->public_key, $dest->public_key] );
    $this->db.save_message(); 
  }

  public function reveal_message($message, $ekeys, $from, $to){
    if (empty($this->secret) or empty($this->encrypted_key)){
      $this->unauthenticate();
      global $data;
      $data->warning = "User was unauthenticated";
    }
    $key = $this->decrypt_priv_key();
    $pos = array_search($this->id , [$from,$to] );
    if ($pos === False){
      return False;
    }
    $privkey =openssl_get_privatekey($key);
    $ok = openssl_open($message, $result, $ekeys[$pos], $key);
    if ($ok){
      return $result;
    }else{
      return False;
    }
    openssl_free_key($key);
  }

  private function generate_pwd(){
    $pwd =  $_POST['pwd-square'].$_POST['pwd-circle'].$_POST['pwd-triangle'];
    return $pwd; 
  }

  public function unauthenticate(){
    $this->destroy_session();
    $this->authenticated = False;
    unset($_SESSION);
  }


  public function authenticate(){
    $err = [];
    $this->log_in($_POST['username']);
    $pwd = $this->generate_pwd();
    if (password_verify($pwd, $this->password)){
      $this->create_secret($pwd);
      $this->authenticated = True;
      $this->load_by_id($this->id);
      $this->grab_session();
    }else{
      $err[] = 'Something has gone wrong';
    }
    if (empty($err)){
      return True;
    }else{
      $this->err = $err;
      return False;
    }
  }

  private function destroy_session(){
    if (!empty($_COOKIE["wai"])){
      setcookie("wai", "", time()-3600);
      $this->db->destroy_session($this->id, $_COOKIE['wai']);
    }
  }

  private function grab_session(){
    if (empty($_COOKIE['wai'])){
      throw new  \Error("Cookie vazio");
    }
    $this->db->grab_session($this->id, $_COOKIE['wai']);
  }

  private function create_key_pair(){
    $res =  openssl_pkey_new([
      'private_key_bits' => 2048,
      'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);
    if (openssl_pkey_export ($res, $privkey)){
      $this->encrypt_priv_key($privkey);
      if ($this->decrypt_priv_key() != $privkey){
        throw new Error("Error creating key pair encryption");
      }
      $pub = openssl_pkey_get_details($res);
      $this->public_key = $pub["key"];
      return true;
    }
  }

  private function encrypt_priv_key($privkey){
    $output = openssl_encrypt($privkey, $this->method, $this->secret, 0, $this->iv);
    $output = base64_encode($output);
    $this->encrypted_key = $output;
  }
  
  private function decrypt_priv_key(){
    $decoded = base64_decode($this->encrypted_key);
    return openssl_decrypt($decoded, $this->method, $this->secret, 0, $this->iv);
  }

}
