<?php

function init_database(){
    $dbConfigStr = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/config/database.json');
    $dbConfig = json_decode($dbConfigStr, true);

    $database = new Database($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
    return $database;
}

?>