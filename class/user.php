<?php

class User {

  private $Database;

  public function __construct($DatabaseLink){
    $this->Database = $DatabaseLink;
  }

  public function processPassword($password){
    $options = [
    'cost' => 8,
    ];
    return password_hash($password, PASSWORD_BCRYPT, $options);
  }

  public function login($userLogin, $password){
    $userLogin = $this->Database->escape($userLogin);
    $password = $this->Database->escape($password);
    //$password = $this->processPassword($password);

    $result = $this->Database->getArray("SELECT * FROM `user` WHERE `username` = '".$userLogin."' OR `email` = '" . $userLogin. "'");
    if(isset($result[0])){
      //user found
      $user = $result[0];

      if(password_verify($password, $user['password'])){
         //password matched. Login Successful
        $data = array();

        $tokenReturn = $this->createToken($user['id']);
        if($tokenReturn === false){
           $data['status'] = "270";
           $result = json_encode(array($data));
           print_r($result);
           exit();
         }

         $data ['status'] = "100";
         $data ['token'] = $tokenReturn;

         $response = json_encode(array($data));
         return $response;
       }
       else{
         //password didn't matched
         $data = array();
         $data ['status'] = "258";
         $response = json_encode(array($data));
         return $response;
       }
    }
    else {
      //user not found
      $data = array();
      $data ['status'] = "255";
      $response = json_encode(array($data));
      return $response;
      }
  }

  private function createToken($userID){
      $creationTime = time();
      $expiryTime = strtotime('+7 days', $creationTime);

      $token_seed = $userID . "#Heil_Hitler#" . $creationTime . "&Fuhrer_is_Great&";
      $token = hash('sha256', $token_seed);

      $sql = "INSERT INTO login_token (token, uid, creationTime, expiryTime)
              VALUES ('" . $token . "','" . $userID . "','" . $creationTime . "','"
              . $expiryTime. "')";
      if ($this->Database->query($sql) !== false) {
          echo "New token created";
          return $token;
      }
      else {
          return false;
      }
    }
}
