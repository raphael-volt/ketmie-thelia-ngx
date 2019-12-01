<?php
	include_once(realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");

	include_once("../../../classes/Declinaison.class.php");
	include_once("../../../classes/Declinaisondesc.class.php");
	include_once("../../../classes/Declidisp.class.php");
	include_once("../../../classes/Declidispdesc.class.php");
	include_once("../../../classes/Rubrique.class.php");
	include_once("../../../classes/Rubriquedesc.class.php");
	include_once("../../../classes/Exdecprod.class.php");
	include_once("../../../classes/Rubdeclinaison.class.php");

	$action=$_REQUEST['action'];
	if(!isset($action)) die("erreur");
	$declidisp=$_REQUEST['declidisp'];
	if(!isset($declidisp)) die("erreur");
	$declidisp=$_REQUEST['declidisp'];
	if(!isset($declidisp)) die("erreur");
	
	if($action=='activer'){
		echo activerDeclidisp($declidisp);
	} else if($action=='desactiver'){
		echo desactiverDeclidisp($declidisp);
	} else {
		echo "erreur";
	}
	
	function activerDeclidisp($declidisp){
		if (!isset($declidisp)) return;
		$exdecprod=new Exdecprod();
		$query = "select produit from $exdecprod->table where declidisp=$declidisp";
		$resul = mysql_query($query, $exdecprod->link);
		while($row=mysql_fetch_object($resul)){
			$exdecprod=new Exdecprod();
			$exdecprod->charger($row->produit, $declidisp);
			if ($exdecprod->id>0){
				$exdecprod->delete();
			}
		}
		return "La valeur de cette d&eacute;clinaison a &eacute;t&eacute; activ&eacute;e pour tous les produits.";
	}
	function desactiverDeclidisp($declidisp){
		if (!isset($declidisp)) return;
		$exdecprod = new Exdecprod();
		$produit = new Produit();
		$query = "select id from $produit->table";
		$resul = mysql_query($query, $produit->link);
		while($row=mysql_fetch_object($resul)){
			$exdecprod=new Exdecprod();
			$exdecprod->charger($row->id, $declidisp);
			if (!($exdecprod->id>0)){
				$exdecprod->produit=$row->id;
				$exdecprod->declidisp=$declidisp;
				$exdecprod->add();
			}
		}
		return "La valeur de cette d&eacute;clinaison a &eacute;t&eacute; d&eacute;sactiv&eacute;e pour tous les produits.";
	}
?>