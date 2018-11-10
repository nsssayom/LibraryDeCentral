<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/class/database.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/class/user.php');

  $database = new Database("http://27.147.181.30", "phpmyadmin", "Pwd_1234", "network");
  $database->connect();
  $user = new User($database);

  //echo $user->login("admin@softopian.com","1234");
  //echo $user->verifyToken("485e8ab041713413d466ffd99d1d93afdfa4dae5fa2850f839a474f0e8d118cd");
  //echo (int) ($user->isUserNameAvailable("nsssayom"));
  //echo (int) ($user->isUserNameAvailable("admin"));

  //echo (int) ($user->isEmailAvailable("admin@softopian.com"));
  //echo (int) ($user->isEmailAvailable("nsssayom@softopian.com"));

  //echo (int) ($user->isPhoneAvailable("+8801717018376"));
  //echo (int) ($user->isPhoneAvailable("8801521308829"));

  $user->register("admin", "+8801717018376", "admin@softopian.com");
?>
