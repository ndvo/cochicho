<?php

namespace Message;

require_once 'htmlpurifier/library/HTMLPurifier.auto.php';
require_once 'users/get_session.sql';

use \User\User as User;

global $purifier;
if (empty($purifier)){
  $config = HTMLPurifier_Config::createDefault();
  $purifier = new HTMLPurifier($config);
}

class Message{
  public $mid;
  public $from;
  public $to;
  public $title;
  public $body;
  public $time;
  public $err;
  private $dirty_from;
  private $dirty_to;
  private $dirty_title;
  private $dirty_content;
  
  static function is_invalid_envelope_array($e_arr){
    $err = [];
    if (empty($e_arry)){
      $err[] = "Envelope is empty";
    }
    if (!is_array($e_arr)){
      $err[] = "The envelope is not is the proper format (PHP array).";
    }
    if (empty($e_arr['from'])){
      $err[] = "The sender (from) is missing";
    }
    if (empty($e_arr['to'])){
      $err[] = "The recipient (to) is missing";
    }
    if (empty($e_arry)){
      $err[] = "Message is empty";
    }
    if (!is_array($e_arr)){
      $err[] = "The message is not is the proper format (PHP array).";
    }
    if (empty($e_arr['title'])){
      $err[] = "The message title is missing";
    }
    if (empty($e_arr['body'])){
      $err[] = "The message body is missing";
    }
    if (empty($e_arr['time'])){
      $err[] = "The time is missing";
    }else{
      $v = $e_arr['time'];
      if (!is_numeric($v)){
        $err[] = "Time is not in a valid format";
      }
    }
    if (empty($err)){
      return False;
    }else{
      array_push($this->err, ...$err);
      return $err;
    }
  }

  static function htmlsafe($string){
    $string = $purifier->purify($string);
    return $string; 
  }
  
  public function __construct($mid = False , $envelope=[]){
    if ($mid == False){
      if (self::is_invalid_envelope_array($message)){
        throw new Error("Invalid message array. Messages must be either built from an id or from envelope array.");
      }
      $this->build_from_values($envelope);
    }else{
      $m = $db->retrieve_message($mid);
    }
  }

  private function build_from_values($e_arr){
    $from = $e_arr['from'];
    $to = $e_arr['to'];
    $title = $e_arr['title'];
    $body = $e_arr['body'];
    $time = $e_arr['time'];
    $this->dirty_from = User::is_valid_name($from);
    $this->dirty_to = User::is_valid_name($to);
    $this->dirty_title = $title;
    $this->dirty_body = $body;
    // Safe values
    $this->time = intval($time);
    $this->to = self::htmlsafe($to);
    $this->from = self::htmlsafe($from);
    $this->title = self::htmlsafe($title);
    $this->body = self::htmlsafe($body);
  }

  private function build_envelope(){
    $e = [];
    $e['from'] = $this->dirty_from;
    $e['to'] = $this->dirty_to;
    $e['title'] = $this->dirty_title;
    $e['body'] = $this->dirty_body;
    $e['time'] = $this->time;
    return $e;
  }

  public function build_from_id($id){
    $db->message_from_id($id);
  }


  private function user_to_name($name){
    if (is_object($name)){
      if (!empty($name->name)){
        $name = $name->name;
      }else{
        return False;
      }
    }
    if (User::is_valid_name($name)){
      return $name;
    }
  }

  public function store_message(){
    $message = serialize($this->build_envelope());
    $from = new User($this->dirty_from);
    $to = new User($this->dirty_to);
    $ekeys = [$from->id=>$from->public_key, $to->id=>$to->public_key];
    openssl_seal( $message, $sealed, $ekeys );
    $db->message_store($from->id, $to->id, $encrypted_message, serialize($ekeys) );
    $to = $this->dirty_to;
  }


  private function decrypt_message($message, $ekeys){
    global $user;
    $e_arr = $user->reveal_message($message, $ekeys);
    if ($e_arr){
      $this->build_from_values($e_arr);
    }else{
      $this->err[] = 'User could not decrypt the message';
    }
  }

}


