<?php

include_once 'client/config_thelia.php';
$user = THELIA_BD_LOGIN;
$pwd = THELIA_BD_PASSWORD;
$host = THELIA_BD_HOST;
$name = THELIA_BD_NOM;
$filename = __DIR__ . "/amf/dump.sql";
$res = exec("mysqldump --user=$user --password='$pwd' --host=$host $name > $filename");

header( "Content-Type: text/plain");
header("Content-Length: " . filesize($filename));
header( "Content-Disposition: attachment; filename=\"$filename\"");
echo file_get_contents($filename);