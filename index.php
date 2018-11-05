<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/class/database.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/class/user.php');

  $database = new Database("localhost", "phpmyadmin", "Pwd_1234", "network");
  $database->connect();
  $user = new User($database);

  //echo $user->login("admin@softopian.com","1234");
  echo $user->verifyToken("485e8ab041713413d466ffd99d1d93afdfa4dae5fa2850f839a474f0e8d118cd");

?>
