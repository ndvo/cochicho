<?php

namespace User;
require_once( 'config/db.php');

require_once('config/db.php');

use \DB\Conn as D;


class User{
  private $id;
  public $name;
  public $mail;
  public $public_key;
  private $authenticated;
  private $iv;
  private $method = "AES-256-CBC";
  private $private_key;
  public $err;
  private $db;
  private $password;

  public function __construct(){
    $this->err = [];
    $this->db = D::get();
    $logged_in = $this->am_i_in();
  }

  public function register(){
    $err = [];
    if (!empty($_POST)){
      $this->name = $_POST['username'];
      $this->mail = $_POST['mail'];
      $umailconfirm = $_POST['mail2'];
      if (empty($_POST['pwd-square']) ||  empty($_POST['pwd-circle']) || empty($_POST['pwd-triangle'])){
        $err[] = 'Please, provide the 3 passwords';
      }
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
        $this->secret = hash('sha256', $pwd);
        $iv = random_bytes(16);
        $ok = $this->create_key_pair();
        if ($ok){
          $this->db->insert_user($this->mail, $this->name, $pwd, $this->public_key, 1);
          return True;
        }
      }
    }
    $this->err = $err;
    return False;
  }

  private function log_in($name){
    $u = $this->db->basic_user_by_name($name);
    if (empty($u)){
      $this->name = "Anonymous";
      return False;
    }else{
      $this->name = $u['name'];
      $this->password = $u['password'];
      $this->id = $u['id'];
      $this->authenticated = true; 
    }
    return True;
  }

  public function am_i_in(){
    $c =$_COOKIE['wai'];
    if (empty($c)){
      setcookie('wai',random_bytes(256), time()+1*60*60*24, '/', 'security', $secure=false, $httponly=true  );
      return False;
    }else{
      $uid = $this->db->get_session($c);
      if (empty($uid)){
        return False;
      }else{
        $u = $this->db->basic_user_by_id($uid);
        $this->name = $u['name'];
        $this->password = $u['password'];
        $this->id = $u['id'];
        return True;
      }
    }
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

  public function cipher_message($message){
  }


  private function sign_message($message){
  }

  public function send_message($message, $dest){
    $sealed = "";
    $env_keys = [];
    openssl_seal( $message, $sealed_data , $env_keys , [$this->public_key, $dest->public_key] );
    $this->db.save_message(); 
  }

  private function reveal_message($message, $envelope){
    $key = decrypt_priv_key();
  }

  private function generate_pwd(){
    $pwd =  $_POST['pwd-square'].$_POST['pwd-circle'].$_POST['pwd-triangle'];
    return $pwd; 
  }
  public function authenticate(){
    $err = [];
    $this->log_in($_POST['username']);
    $pwd = $this->generate_pwd();
    if (password_verify($pwd, $this->password)){
      $this->authenticated = True;
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
      $this->private_key = $privkey;
      $pub = openssl_pkey_get_details($res);
      $this->public_key = $pub["key"];
      return true;
    }
  }

  private function encrypt_priv_key(){
    $output = openssl_encrypt($this->privkey, $this->method, $this->secret, 0, $this->iv);
    $output = base64_encode($output);
    $this->encrypted_key = $output;
  }

  private function decrypt_priv_key(){
    return openssl_decrypt(base64_decode($this->encrypted_key), $this->method, $this->secret, 0, $this->iv);
  }

}
