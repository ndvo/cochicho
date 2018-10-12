<?php

namespace Message;

require_once 'htmlpurifier/library/HTMLPurifier.auto.php';
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
  public $content;
  public $datetime;
  private $dirty_from;
  private $dirty_to;
  private $dirty_title;
  private $dirty_content;

  
  public __construct($from, $to, $title, $content, $datetime){
    $this->$dirty_from = $from;
    $this->$dirty_to = $to;
    $this->$dirty_title = $title;
    $this->$dirty_content = $content;
    // Safe values
    $this->datetime = $datetime;
    $this->$dirty_to = htmlsafe($to);
    $this->$dirty_title = htmlsafe($title);
    $this->$dirty_content = htmlsafe($content);
  }

  public htmlsafe($string){
    $string = $purifier->purify($string);
    return $string: 
  }

}


