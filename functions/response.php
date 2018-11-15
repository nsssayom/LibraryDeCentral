<?php

function response_ok(){
    $data = array();
    $data['status'] = "100";
    return json_encode(array($data));
}

function response_database_error(){
    $data = array();
    $data['status'] = "400";
    return json_encode(array($data));
}

function response_invalid_request(){
    $data = array();
    $data['status']="400";
    return json_encode(array($data));
}


