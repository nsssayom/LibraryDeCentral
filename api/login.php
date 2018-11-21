<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/init_database.php');

$database = init_database();
$database->connect();
$user = new User($database);

if (isset($_POST['user_login']) && isset($_POST['password'])) {

    $user_login = $database->escape($_POST['user_login']);
    $password = $_POST['password'];
    $user->login($user_login, $password);

} else {
    response_invalid_request();
}

?>