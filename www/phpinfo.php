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
        "remote_port", 
        "remote_mode", 
        "remote_host",
        "remote_handler",
        "remote_enable",
        "remote_mode",
        "remote_connect_back",
        "remote_autostart",
        "remote_autostart",
        "show_exception_trace", 
        "show_error_trace", 
        "profiler_enable",
        "idekey"
    );
    echo "<table><caption style='text-align:left;'>XDebug config</caption><tr><th>Name</th><th>Value</th></tr>";
    $v = "'" . ini_get('zend_extension') . "'";
    echo "<tr><td>zend_extension</td><td>{$v}</td></tr>";
    foreach ($l as $key) {
        $v = ini_get('xdebug.'.$key);
        if($v == "1"||$v=="0")
            $v = $v == "1" ? "On":"Off";
        echo "<tr><td>{$key}</td><td>{$v}</td></tr>";
    }
    echo "</table>";
}

xdebugInfos();
echo "</div>";
phpinfo();
?>




</body>
</html>