<?php
include_once($_SERVER['DOCUMENT_ROOT'] .'/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] .'/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');

$dbConfigStr = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/config/database.json');
$dbConfig = json_decode($dbConfigStr, true);

$database = new Database($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
$database->connect();
$user = new User($database);

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == "checkEmail"){
      if (isset($_POST['email']) && !empty($_POST['email'])){
        $response = $user->isEmailAvailable($_POST['email']);
        if (isJson($response)){
          echo $response;
        }
        else{
          $data = array();
          $data['status'] = "100";
          $response = json_encode(array($data));
          echo $response;
        }
      }
    }

    else if ($action == "register"){
      //("Arnika", "4621", "+8801750252963", "arnikaroyrumu@gmail.com", "Arnika Roy Rumu", "1997-09-13")
      if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['phone'])&&
          isset($_POST['email']) && isset($_POST['name']) && isset($_POST['dob'])){
            echo $user->register( $_POST['username'],
                                  $_POST['password'],
                                  $_POST['phone'],
                                  $_POST['email'],
                                  $_POST['name'],
                                  $_POST['dob']);
          }
    }
}

?>
