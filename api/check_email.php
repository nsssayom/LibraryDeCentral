<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/init_database.php');

$database = init_database();
$database->connect();

$user = new User($database);

if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = $database->escape($_POST['email']);
    $result = $user->isEmailAvailable($email);
    response_ok();
}
 else {
    response_invalid_request();
}


?>