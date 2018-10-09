<?php
namespace User;


class User{
  public $name;
  public $public_key;
  private $authenticated;
  private $iv;
  private $method = "AES-256-CBC";

  public function register(){
    if (isset($_POST['submit'])){
      $this->uname = $_POST['uname'];
      $this->umail = $_POST['umail'];
      $umailconfirm = $_POST['umailconfirm'];
      if ($this->umail != $umailconfirm){
        $err[] = 'Please, be sure to input the same address in both email and confirm email fields.';
      }
      if (!filter_var($this->umail, FILTER_VALIDATE_EMAIL)){
        $err[] = 'There is something wrong with the provided email.';
      }
      if (empty($err)){
        $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
        $this->secret = hash('sha-256', $_POST['pwd']);
        $iv = random_bytes(16);
        create_key_pair($_POST['pwd']);

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
    db.save_message(); 
  }

  private function reveal_message($message, $envelope){
    $key = decrypt_priv_key();
    if ()
  }

  public function authenticate(){
    if (password_verify($_POST['pwd'], $this->pwd)){
      $this->authenticated = True;
    }
  }

  private function create_key_pair{
    $res =  openssl_pkey_new([
      'private_key_bits' => 2048,
      'private_key_type' => OPENSSL_KEYTYPE_RSA,
    ]);
    #TODO: complete function
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
