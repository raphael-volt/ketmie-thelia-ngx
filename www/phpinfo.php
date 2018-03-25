<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>phpinfo</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    width: 100%;
}

.main {
    width: 960px;
    margin: 0 auto;
    padding: 0;
}

.main>* {
    margin-left: 13px;
    margin-right: 13px;
}

.main tr>th:first-child, .main tr>td:first-child {
    width: 300px;
}
</style>
</head>
<body>
<div class="main">
<?php
echo "<h1>php7server works!</h1>";

function xdebugInfos()
{
    $l = array(
        "remote_enable" => ini_get('xdebug.remote_enable'),
        "show_exception_trace" => ini_get('xdebug.show_exception_trace'),
        'remote_port' => ini_get('xdebug.remote_port'),
        'remote_connect_back' => ini_get('xdebug.remote_connect_back'),
        'autostart' => ini_get('xdebug.remote_autostart'),
        'remote_handler' => ini_get('xdebug.remote_handler'),
        'remote_host' => ini_get('xdebug.remote_host'),
        'remote_handler' => ini_get('xdebug.remote_handler'),
        'idekey' => ini_get('xdebug.idekey')
    );
    echo "<table><caption style='text-align:left;'>XDebug config</caption><tr><th>Name</th><th>Value</th></tr>";
    foreach ($l as $key => $value) {
        echo "<tr><td>{$key}</td><td>{$value}</td></tr>";
    }
    echo "</table>";
}

xdebugInfos();
echo "</div>";
phpinfo();
?>


</body>
</html>