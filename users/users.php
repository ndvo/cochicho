<?php

namespace User;
require_once( 'config/db.php');

require_once('config/db.php');

use \DB\Conn as D;

class User{
  public $name;
  public $mail;
  public $public_key;
  private $authenticated;
  private $iv;
  private $method = "AES-256-CBC";
  private $private_key;
  public $err;

  public function register(){
    $err = [];
    if (!empty($_POST)){
      $this->name = $_POST['username'];
      $this->mail = $_POST['mail'];
      $umailconfirm = $_POST['mail2'];
      if (empty($_POST['pwd-square']) ||  empty($_POST['pwd-circle']) || empty($_POST['pwd-triangle'])){
        $err[] = 'Please, provide the 3 passwords';
      }
      $pwd = password_hash($_POST['pwd-square'].$_POST['pwd-circle'].$_POST['pwd-triangle'], PASSWORD_DEFAULT);
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
          $db = D::get();
          $db->insert_user($this->mail, $this->name, $pwd, $this->public_key, 1);
        }
      }
    }
    $this->err = $err;
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
    db.save_message(); 
  }

  private function reveal_message($message, $envelope){
    $key = decrypt_priv_key();
  }

  public function authenticate(){
    if (password_verify($_POST['pwd'], $this->pwd)){
      $this->authenticated = True;
    }
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
