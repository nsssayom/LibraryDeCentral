<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/init_database.php');

$database = init_database();
$database->connect();

$user = new User($database);

if (isset($_POST['username']) && !empty($_POST['username']) ) {
    $username = $database->escape($_POST['username']);
    $result = $user->isUserNameAvailable($username);
    if($result) response_ok();
} else {
    response_invalid_request();
}


?>