<?php
	include_once(realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");
	autorisation("declimax");
?>
<?php
	include_once("pre.php");
	include_once("auth.php");

	include_once("../classes/Declinaison.class.php");
	include_once("../classes/Declinaisondesc.class.php");
	include_once("../classes/Declidisp.class.php");
	include_once("../classes/Declidispdesc.class.php");
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Rubriquedesc.class.php");
	include_once("../classes/Exdecprod.class.php");
	include_once("../classes/Rubdeclinaison.class.php");

	if(!isset($lang)) $lang=1;
	$action=$_REQUEST['action'];
	if(!isset($action)) $action="";
	
	if($action=='modifier' && isset($_REQUEST['declimax'])){
		modifierRubdeclinaison($_REQUEST['id'],$_REQUEST['rubriques']);
	}
	
	function modifierRubdeclinaison($declinaison,$rubriques){
		if (!isset($declinaison)) return;
		if (!isset($rubriques)) return;
	
		$rub=new Rubrique();
		$query = "select id from $rub->table";
		$resul = mysql_query($query, $rub->link);
		while($row=mysql_fetch_object($resul)){
			if (in_array($row->id,$rubriques)){
				$rubdec=new Rubdeclinaison();
				$rubdec->charger($row->id, $declinaison);
				if(!$rubdec->id>0){
					$rubdec=new Rubdeclinaison();
					$rubdec->declinaison=$declinaison;
					$rubdec->rubrique=$row->id;
					$rubdec->add();
				}
			} else {
				$rubdec=new Rubdeclinaison();
				$rubdec->charger($row->id, $declinaison);
				if($rubdec->id>0){
					$rubdec->delete("delete from $rubdec->table where declinaison=\"$declinaison\" AND rubrique=\"$row->id\"");
				}
			}
		}
	}
?>
