<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/class/database.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/class/user.php');

  $database = new Database("localhost", "phpmyadmin", "Pwd_1234", "network");
  $database->connect();
  $user = new User($database);

  echo $user->login("admin@softopian.com","1234");

?>
