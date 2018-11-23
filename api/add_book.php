<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/database.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/class/user.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/validator.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/LibraryDeCentral/functions/init_database.php');

$database = init_database();
$database->connect();

$user = new User($database);
$library = new Library($database);

if (isset($_POST['token']) && !empty($_POST['token']) &&
    isset($_POST['title']) && isset($_POST['authors']) &&
    isset($_POST['authorType']) && count($_POST['authors']) == count($_POST['authorType'])) {

    $params = array(); //initializing the parameter array

    $params['userID'] = $user->verifyToken($_POST['token']);
    $params['title'] = $_POST['title'];

    //building array for passing authors with associative index name and type
    $authorType = $_POST['authorType'];
    $authors = array();
    $i = 0;
    foreach ($_POST['authors'] as $author) {
        $authorArr = array();
        $authorArr ['name'] = $author;
        $authorArr['type'] = $authorType[$i++];
        array_push($authors, $authorArr);
    }
    $params['authors'] = $authors;


    if (isset($_POST['genre'])) {
        $params['genre'] = $_POST['genre'];
    }
    if (isset($_POST['publisher'])) {
        $params['publisher'] = $_POST['publisher'];
    }
    if (isset($_POST['tags'])) {
        $params['tags'] = $_POST['tags'];
    }

    $library->AddBook($params);
} else {
    response_invalid_request();
}