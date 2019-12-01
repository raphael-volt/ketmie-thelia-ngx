<?php
include_once (realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");

include_once (realpath(dirname(__FILE__)) . "/Decliprix.class.php");

$dpx = new Decliprix();
$service = $dpx->service;
autorisation($service->getPluginNom());

$service->declinaisonAction($GLOBALS['id'], $_POST['action']);
?>


