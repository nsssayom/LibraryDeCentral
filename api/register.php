<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/init_database.php');

$database = init_database();
$database->connect();
$user = new User($database);

$username = $database->escape($_POST['username']);
$password = $_POST['password'];
$phone = $database->escape($_POST['phone']);
$email = $database->escape($_POST['email']);
$name = $database->escape($_POST['name']);
$dob = $_POST['dob'];
$sex = $database->escape($_POST['sex']);

if (!validateDate($dob, 'YYYY-MM-DD')) {
    response_invalid_dateofbirth();
}


//("Arnika", "4621", "+8801750252963", "arnikaroyrumu@gmail.com", "Arnika Roy Rumu", "1997-09-13")
if (isset($_POST['username']) &&
    isset($_POST['password']) &&
    isset($_POST['phone']) &&
    isset($_POST['email']) &&
    isset($_POST['name']) &&
    isset($_POST['dob']) &&
    isset($_POST['sex'])) {
    $user->register(
        $username,
        $password,
        $phone,
        $email,
        $name,
        $dob,
        $sex
    );
}
?>