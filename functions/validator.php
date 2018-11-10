<?php
function validateEmail($email)
{
    if ((!empty($email) && (!filter_var($email, FILTER_VALIDATE_EMAIL)))
        || strlen($email > 120)) {
        /*$data = array();
        $data ['status'] = "260";
        $result = json_encode(array($data));
        print_r($result);*/
        return false;
    }
    return true;
}

function validateName($userName)
{
    if ((!empty($userName) && (!preg_match("/^[a-zA-Z ]*$/", $userName))) || strlen($userName > 70)) {
        /*$data = array();
        $data ['status'] = "265";
        $result = json_encode(array($data));
        print_r($result);*/
        return false;
    }
    return true;
}


function validatePhone($phone)
{
    if (!empty($phone) && preg_match("/^[+][1-9][0-9]{3,14}$/", $phone)) {
        /*$data = array();
        $data ['status'] = "265";
        $result = json_encode(array($data));
        print_r($result);*/
        return true;
    }
    return false;
}

function isJson($string) {
  if(($string[0] == "[" && substr($string, -1) == "]") ||
    ($string[0] == "{" && substr($string, -1) == "}")){
    return true;
  }
  return false;
}

?>
