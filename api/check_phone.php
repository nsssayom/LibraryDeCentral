<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/init_database.php');

$database = init_database();
$database->connect();

$user = new User($database);

if (isset($_POST['phone']) && !empty($_POST['phone'])) {
    $phone = $database->escape($_POST['phone']);
    $result = $user->isPhoneAvailable($phone);
} else {
    response_invalid_request();
}


?>