<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/init_database.php');

$database = init_database();
$database->connect();

$user = new User($database);
$book = new Book($database);

if (isset($_POST['token']) && !empty($_POST['token']) &&
    isset($_POST['bookId']) && isset($_POST['newTitle'])) {
    $book->editBookTitle($_POST['bookId'], $_POST['newTitle']);
} else {
    response_invalid_request();
}
