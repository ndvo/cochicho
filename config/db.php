<?php

namespace DB;

class Conn{
  
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

  private function connect(){
    if ($this->pdo == null){
      try{
        $this->pdo = new \PDO("sqlite:db/scratch.db");
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      }catch (Exception $e){
        echo "Error";
        die("Connection failed: ".$e->getMessage());
      }
    }
    return $this->pdo;
  }

  public function install(){
    $query = file_get_contents('db/install.sql');
    $affected = $this->pdo->exec($query);
    if ($affected === false){
      $err = $this->pdo->errorInfo();
      print_r( $err);
    }
    echo "done";
  }


  public function sql($queries){
    $this->pdo->beginTransaction();
    foreach ($queries as $q){
      $this->pdo->query($this->sanitize($q));
    }
    $this->pdo->commit();
  }

  public function insert_user($mail, $name, $password, $pubkey, $terms){
    $query = file_get_contents('db/create_user.sql');
    $query = $this->pdo->prepare($query);
    $query->bindParam(':mail', $name);
    $query->bindParam(':name', $name);
    $query->bindParam(':password', $name);
    $query->bindParam(':pubkey', $name);
    $query->bindParam(':privkey', $name);
    $query->bindParam(':terms', $name);

  }
}
