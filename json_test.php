<?php

if (!isset($_GET['a'], $_GET['b'])){
  $data = array();
  $data ['error'] ="Wrong Parameter";
  $result = json_encode(array($data));
  print_r($result);
  exit();
}

$data = array();
$data ['a'] = $_GET['a'];
$data ['b'] = $_GET['b'];
$data ['sum'] = (int) $_GET['a'] + (int) $_GET['b'];
$result = json_encode(array($data));
print_r($result);
?>
