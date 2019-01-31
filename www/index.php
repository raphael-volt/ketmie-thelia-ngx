<?php

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, DELETE, OPTIONS");
    // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Api-Session-Id, Authorization");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, X-Api-Session-Id");
    header("HTTP/1.1 200 OK");
    exit();
}

if (isset($_SERVER['HTTP_X_API_SESSION_ID'])) {
    $id = $_SERVER['HTTP_X_API_SESSION_ID'];
    if ($id && strlen($id) > 0)
        session_id($id);
}

unset($fond);
require ("fonctions/moteur.php");
?>