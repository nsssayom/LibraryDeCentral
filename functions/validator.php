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

?>
