<?php

function response_ok(){
    $data = array();
    $data['status'] = "100";
    echo json_encode(array($data));
    exit();
}

function response_database_error(){
    $data = array();
    $data['status'] = "400";
    echo json_encode(array($data));
    exit();
}

function response_invalid_request(){
    $data = array();
    $data['status']="330";
    echo json_encode(array($data));
    exit();
}

function response_invalid_user(){
    $data = array();
    $data ['status'] = "255";
    echo json_encode(array($data));
    exit();
}

function response_wrong_password(){
    $data = array();
    $data ['status'] = "258";
    echo json_encode(array($data));
    exit();
}

function response_token_creation_failed(){
    $data = array();
    $data['status'] = "270";
    echo json_encode(array($data));;
    exit();
}

function response_token($token){
    $data = array();
    $data ['status'] = "100";
    $data ['token'] = $token;
    return json_encode(array($data));
    exit();
}

function response_token_update_failed(){
    $data = array();
    $data['status'] = "275";
    echo json_encode(array($data));
    exit();
}

function response_token_expired(){
    $data = array();
    $data['status'] = "280";
    echo json_encode(array($data));
    exit();
}

function response_invalid_token(){
    $data = array();
    $data['status'] = "285";
    echo json_encode(array($data));
    exit();
}
