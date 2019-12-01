<?php
	include_once(realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");

	autorisation("decliprix");

	include_once(realpath(dirname(__FILE__)) . "/DecliPrix.class.php");

	$decliprix = new DecliPrix();

	if(isset($action)){
		if($action == "supprimer"){
			$decliprix->charger($id);
			$decliprix->delete();
		}
		else if($action == "valider"){
			$decliprix->valider($id);
		}

		$cache = new Cache();
		$cache->vider("COMMENTAIRES", "%");
	}
?>
<h2>Liste des déclinaisons par prix</h2>

<table style="width: 100%;">
	<tr style="background: #333; color: #fff;">
		<th width="150px">Prix</th>
		<th width="100px">Ref</th>
		<th width="100px">declidisp</th>
	</tr>

<?php
	$query = "select * from $decliprix->table";
	$resul = $decliprix->query($query, $decliprix->link);
	$i = 0;

	while($resul && $row = $decliprix->fetch_object($resul)){
			$fond = ($i%2) ? "#ececec" : "#cdcdcd";
  			$i++;
?>

	<tr style="background: <?php echo $fond; ?>;">
		<td align="center"><?php echo $row->prix; ?></td>
		<td align="center"><?php echo $row->ref; ?></td>
		<td align="center"><?php echo $row->id_declidisp; ?></td>
		<td align="center">

<?php if(!$row->etat) {?>

			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?nom=decliprix&action=valider&id=<?php echo $row->id; ?>">
			<img src="../template/_gfx/valider.png" alt="" title="désactiver"/></a> &nbsp; &nbsp;

<?php } else { ?>

			<img src="../template/_gfx/valider-ok.png" alt="" title="activé"/></a> &nbsp; &nbsp;

<?php } ?>

			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?nom=decliprix&action=supprimer&id=<?php echo $row->id; ?>">
			<img src="../template/_gfx/croix.png" alt="" title="supprimer le commentaire"/></a>
		</td>
	</tr>

<?php } ?>

</table>