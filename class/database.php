<?php
  class Database{
    protected $link;
    protected $hostname;
    protected $username;
    protected $password;
    protected $database;

    function __construct($host, $user, $pass, $database){
      $this->hostname = $host;
      $this->username = $user;
      $this->password = $pass;
      $this->database = $database;
      $this->link = NULL;
    }

    function connect(){
      /* Connect To MySQL */
      $mysql = new mysqli($this->hostname, $this->username, $this->password, $this->database);
      if($mysql->connect_error){
        $data = array();
        $data['status'] = "400";
        $result = json_encode(array($data));
        print_r($result);
        return false;
      }else{
        $this->link = $mysql;
        return true;
      }
    }

    function query($sql){
      /* Returns MySQL Query */
      $result = $this->link->query($sql) or $result = false;
      return $result;
    }

    function escape($data){
      // Escape String
      return $this->link->real_escape_string($data);
    }

    function getArray($sql){
      $mq = $this->query($sql);
      if($mq->num_rows > 0){
        $result = array();
        while($mfa = $mq->fetch_array(MYSQLI_ASSOC)){
          $result[] = $mfa;
        }
        return $result;
      }else{
        return false;
      }
    }

    function getLink(){
      return $this->link;
    }

  }
?>
