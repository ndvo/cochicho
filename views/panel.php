<?php

namespace views;

class Panel{
  public $header;
  public $warnings;
  public $received_messages;
  public $sent_messages;
  public $conversations;
  public $contacts;
  public $content;
    
  public function __construct($user){
    $this->header = build_header($user) ;
    $this->warnings = build_warnings($user) ;
    $this->received_messages = build_received_messages($user) ;
    $this->sent_messages = build_sent_messages($user) ;
    $this->conversations = build_conversations($user) ;
    $this->contacts = build_contacts($user) ;
    $this->contents = $this->conversations ;
  }

  public function build_header($user) {
    return "";
  }
  public function build_warnings($user) {
    return "";
  }
  public function build_received_messages($user) {
    return "";
  }
  public function build_sent_messages($user) {
    return "";
  }
  public function build_conversations($user) {
    return "";
  }
  public function build_contacts($user) {
    return "";
  }

}
