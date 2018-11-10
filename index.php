<?php
  include_once($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/class/database.php');
  include_once($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/class/user.php');

  $dbConfigStr = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/LibraryDeCentral/config/database.json');
  $dbConfig = json_decode($dbConfigStr, true);

  $database = new Database($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
  $database->connect();
  $user = new User($database);

  //echo $user->login("admin@softopian.com","1234");
  echo $user->register("Arnika", "4621", "+8801750252963", "arnikaroyrumu@gmail.com", "Arnika Roy Rumu", "1997-09-13");
  //echo $user->verifyToken("485e8ab041713413d466ffd99d1d93afdfa4dae5fa2850f839a474f0e8d118cd");
  //echo (int) ($user->isUserNameAvailable("nsssayom"));
  //echo (int) ($user->isUserNameAvailable("admin"));

  //echo (int) ($user->isEmailAvailable("admin@softopian.com"));
  //echo (int) ($user->isEmailAvailable("nsssayom@softopian.com"));

  //echo (int) ($user->isPhoneAvailable("+8801717018376"));
  //echo (int) ($user->isPhoneAvailable("8801521308829"));

  //$user->register("admin", "dd", "+8801717018376", "admin@softopian.com");
?>
