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


