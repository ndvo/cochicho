<?php

namespace DB;

class Conn{
  private static $dbpath ="content/content.db" ;
  private $pdo;
  public static $instance;
  public static $statements;

  private function __construct(){
    $this->connect();  
  }

	public static function get(){
		if (!isset(self::$instance)){
			self::$instance = new Conn();
		}
		return self::$instance;
	}

  private function connect($reload = False){
    if ($this->pdo == null or $reload == True){
      try{
        $database_path = 'sqlite:'.self::$dbpath;
        $this->pdo = new \PDO($database_path);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      }catch (Exception $e){
        die("Connection failed: ".$e->getMessage());
      }
    }
    return $this->pdo;
  }

  public function install(){
    rename(self::$dbpath, self::$dbpath.".bkp");
    $this->connect($reload=True);
    $query = file_get_contents('db/install.sql');
    $affected = $this->pdo->exec($query);
    if ($affected === false){
      $err = $this->pdo->errorInfo();
      return $err;
    }
  }


  public function sql($queries){
    $this->pdo->beginTransaction();
    foreach ($queries as $q){
      $this->pdo->query($this->sanitize($q));
    }
    $this->pdo->commit();
  }

  public function insert_user($mail, $name, $password, $pubkey, $privkey, $iv, $terms){
    $query = file_get_contents('db/create_user.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':mail', $mail);
    $query->bindParam(':name', $name);
    $query->bindParam(':password', $password);
    $query->bindParam(':pubkey', $pubkey);
    $query->bindParam(':privkey', $privkey);
    $query->bindParam(':iv', $iv);
    $query->bindParam(':terms', $terms);
    $query->execute();
    $query->closeCursor();
  }

  public function full_user_by_name($name){
    $query = file_get_contents('db/user/full_by_name.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':name', $name);
    $query->execute();
    $result =  $query->fetch();
    $query->closeCursor();
    return $result;
  }

  public function full_user_by_id($id){
    $query = file_get_contents('db/user/full_by_id.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':id', $id);
    $query->execute();
    $result =  $query->fetch();
    $query->closeCursor();
    return $result;
  }

  public function basic_user_by_id($id){
    $query = file_get_contents('db/user/basic_by_id.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':id', $id);
    $query->execute();
    $result =  $query->fetch();
    $query->closeCursor();
    return $result;
  }

  public function basic_user_by_name($name){
    $query = file_get_contents('db/user/basic_by_name.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':name', $name);
    $query->execute();
    $result =  $query->fetch();
    $query->closeCursor();
    return $result;
  }

  public function all_usernames(){
    $query = file_get_contents('db/user/all_names.sql');
    $query = $this->pdo->prepare($query);
    $query->execute();
    $result = $query->fetchAll();
    $query->closeCursor();
    return $result;
  }

  public function destroy_session($uid, $session){
    $query = file_get_contents('db/user/destroy_session.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':u', $uid);
    $query->bindParam(':c', $session);
    $result = $query->execute();
    $query->closeCursor();
    return $result;
  }

  public function grab_session($uid, $session){
    $query = file_get_contents('db/user/grab_session.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':u', $uid);
    $query->bindParam(':c', $session);
    $result = $query->execute();
    $query->closeCursor();
    return $result;
  }

  public function get_session($cookie){
    $query = file_get_contents('db/user/get_session.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':cookie', $cookie);
    $query->execute();
    $result = $query->fetch();
    $query->closeCursor();
    return $result;
  }

  public function store_message($from, $to, $message, $ekeys){
    $query = file_get_contents('db/message/store_message.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':from', $from);
    $query->bindParam(':to', $to);
    $query->bindParam(':message', $message);
    $query->bindParam(':ekeys', $ekeys);
    $now = time();
    $query->bindParam(':time', $now);
    $result = $query->execute();
    $query->closeCursor();
    return $result;
  }

  public function retrieve_message($mid){
    $query = file_get_contents('db/message/message.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':mid', $mid);
    $result = $query->execute();
    $result = $query->fetchAll();
    $query->closeCursor();
    return $result;
  }

  public function retrieve_user_messages($uid){
    $query = file_get_contents('db/message/user_messages.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':uid', $uid);
    $result = $query->execute();
    $result = $query->fetchAll();
    $query->closeCursor();
    return $result;
  }

}
