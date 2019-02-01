<?php
require_once 'classes/Cnx.class.php';

StaticConnection::getHandle();

function getPDO() {
    return StaticConnection::$pdo;
}
function mysql_query($query, $link=null)
{
    $pdo = getPDO();
    return $pdo->query($query);
}
function mysql_result($dbhandle, $row = 0, $field = 0)
{
    $cnx = new Cnx();
    return $cnx->get_result($dbhandle, $row, $field);
}
/**
 * @param PDOStatement $dbhandle
 */
function mysql_num_rows($dbhandle) {
    return $dbhandle->rowCount();
}
/**
 * @param PDOStatement $dbhandle
 */
function mysql_fetch_object($dbhandle, $cls=stdClass::class, $params=null) {
    
    return $dbhandle->fetchObject($cls, $params);
}
?>