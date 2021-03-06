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

    $userID = $user->verifyToken($_POST['token']);

    $book_id = $_POST['bookId'];
    $sql = "SELECT `user_id` FROM book_user WHERE book_id = '$book_id'";
    $result = $database->getArray($sql);

    if ($result['user_id'] == $userID){
        $book->editBookTitle($_POST['bookId'], $_POST['newTitle']);
    }
    else{
        response_permission_invalid();
    }


} else {
    response_invalid_request();
}
?>