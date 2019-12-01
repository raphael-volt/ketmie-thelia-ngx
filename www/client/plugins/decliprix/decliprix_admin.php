<?php

include_once (realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");

include_once (realpath(dirname(__FILE__)) . "/Decliprix.class.php");

$dpx = new Decliprix();
$service = $dpx->service;
autorisation($service->getPluginNom());
?>
<div id="contenu_int">
	<?php $service->action();?>
</div>
