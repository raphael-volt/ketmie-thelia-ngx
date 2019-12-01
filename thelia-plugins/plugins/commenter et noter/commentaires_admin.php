<?php
	include_once(realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");

	autorisation("commentaires");

	include_once(realpath(dirname(__FILE__)) . "/Commentaires.class.php");

	$commentaires = new Commentaires();

	if(isset($action)){
		if($action == "supprimer"){
			$commentaires->charger($id);
			$commentaires->delete();
		}
		else if($action == "valider"){
			$commentaires->valider($id);
		}

		$cache = new Cache();
		$cache->vider("COMMENTAIRES", "%");
	}
?>

<h2>Liste des commentaires</h2>

<table style="width: 100%;">
	<tr style="background: #333; color: #fff;">
		<th width="150px">Date</th>
		<th width="100px">Ref. produit</th>
		<th width="100px">Nom</th>
		<th>Message</th>
		<th width="50px">Note</th>
		<th width="100px">Actions</th>
	</tr>

<?php
	$query_commentaires = "select * from $commentaires->table order by date desc";
	$resul_commentaires = $commentaires->query($query_commentaires, $commentaires->link);
	$i = 0;

	while($resul_commentaires && $row = $commentaires->fetch_object($resul_commentaires)){
			$fond = ($i%2) ? "#ececec" : "#cdcdcd";
  			$i++;
?>

	<tr style="background: <?php echo $fond; ?>;">
		<td align="center" style="padding: 10px;"><?php echo date("d-m-Y H:i", $row->date); ?></td>
		<td align="center"><?php echo $row->ref; ?></td>
		<td align="center"><?php echo $row->nom; ?></td>
		<td><?php echo $row->message; ?></td>
		<td align="center"><?php echo $row->note; ?></td>
		<td align="center">

<?php if(!$row->etat) {?>

			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?nom=commentaires&action=valider&id=<?php echo $row->id; ?>"><img src="../template/_gfx/valider.png" alt="" title="valider ce commentaire"/></a> &nbsp; &nbsp;

<?php } else { ?>

			<img src="../template/_gfx/valider-ok.png" alt="" title="commentaire validé"/></a> &nbsp; &nbsp;

<?php } ?>

			<a href="<?php echo $_SERVER['PHP_SELF'] ?>?nom=commentaires&action=supprimer&id=<?php echo $row->id; ?>"><img src="../template/_gfx/croix.png" alt="" title="supprimer le commentaire"/></a>
		</td>
	</tr>

<?php } ?>

</table>