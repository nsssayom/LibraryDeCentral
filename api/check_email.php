<?php

include_once($_SERVER['DOCUMENT_ROOT'] .'/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] .'/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');

$dbConfigStr = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/config/database.json');
$dbConfig = json_decode($dbConfigStr, true);

$database = new Database($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
$database->connect();
$user = new User($database);

if(isset($_POST['email'])){
        $database=new Database();
        $result=$user->isEmailAvailable($_POST['email']);
}


?>