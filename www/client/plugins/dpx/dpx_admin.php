<?php

include_once (realpath(dirname(__FILE__)) . "/../../../fonctions/authplugins.php");

$dpx = new Dpx();
$pluginNom = $dpx->getNom();

autorisation($pluginNom);

$dpxDPO = $dpx->dpxPDO;
$tpl = new TPLH($dpx->getPDO());

$pluginUrl = "module.php?nom=" . $pluginNom;
$action = isset($_POST['action']) ? $_POST['action'] : TPLH::LISTER;
$isNew = false;
$actionName = 'Créer une déclinaison de prix';

switch ($action) {
    case TPLH::CREER_DPX:
    case TPLH::CREER_DPX_DISPS:
        $dpx->caracteristique = $_POST['caracteristique'];
        $dpx->declinaison = $_POST['declinaison'];
        $dpx->titre = $_POST['titre'];        
        break;
    
    case TPLH::MODIFIER_DPX:
        $dpx->charger_id($_POST['id']);
        $dpx->titre = $_POST["titre"];
        $dpx->maj();
        $dpxDPO->updateDpxdisps($dpx);
        $action = TPLH::LISTER;
        break;
    case TPLH::SUPPRIMER_DPX:
        $dpx->charger_id($_POST['id']);
        $dpx->delete();
        $action = TPLH::LISTER;
        break;
}
switch ($action) {
    case TPLH::CREER:
        $formAction = TPLH::CREER_DPX;
        $isNew = true;
        break;
    case TPLH::CREER_DPX:
        $formAction = TPLH::CREER_DPX_DISPS;
        break;
    case TPLH::CREER_DPX_DISPS:
        $id = $dpx->add();
        $dpx->charger_id($id);
        $dpxDPO->createDpxdisps($dpx);
        $action = TPLH::LISTER;
        break;
    case TPLH::EDITER_DPX:
        $dpx->charger_id($_POST['id']);
        $formAction = TPLH::MODIFIER_DPX;
        break;
    case TPLH::LISTER:
        $formAction = TPLH::CREER;
        $actionName = 'Liste des déclinaisons de prix';
        break;
    
    default:
        ;
        break;
}

include __DIR__ . '/core/admin.css.php';
?>

<form id="<?php echo $tpl->formId; ?>"
	action="<?php echo $pluginUrl; ?>" method="post">
	<?php 
	$tpl->input("action", $formAction);
	if ($action == TPLH::EDITER_DPX) {
	    $tpl->input("id", $dpx->id);
	}
	?>
	
</form>
<div id="contenu_int">
	<p align="left">
		<span class="lien04"> <a href="accueil.php" class="lien04">
			<?php echo trad('Accueil', 'admin'); ?>
		</a>
		</span> <img src="gfx/suivant.gif" width="12" height="9" border="0" />
		<a href="module_liste.php" class="lien04">
		<?php echo trad('Modules', 'admin'); ?>
	</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a
			href="<?php echo $pluginUrl; ?>" class="lien04">
		<?php echo $pluginNom . " [$action]";?>
	</a>
	</p>
	<div
		id="<?php echo $action == "lister" ? "bloc_list":"bloc_description"; ?>">

		<div class="entete_liste_config">

			<div class="titre"><?php echo $actionName;?></div>
<?php
if ($action == TPLH::LISTER) {
    ?>
            	<div class="fonction_ajout">
        	    	<?php $tpl->submitLink("AJOUTER"); ?>
        		</div>
		</div>




		<ul id="Nav">
			<li
				style="height: 25px; width: 777px; border-left: 1px solid #96A8B5;">Titre</li>
			<li
				style="height: 25px; width: 184px; border-left: 1px solid #96A8B5;">Actions</li>
		</ul>
		<div class="bordure_bottom" id="resul">
        			<?php
    $result = $dpx->query_liste("SELECT id FROM " . Dpx::TABLE, Dpx::class);
    if (count($result)) {
        $i = 0;
        foreach ($result as $v) {
            $v instanceof Dpx;
            $v->charger_id($v->id);
            $fedit = "dpxedit" . $v->id;
            $fdelete = "dpxdelete" . $v->id;
            ?>
    					<form method="post" id="<? echo $fedit ?>"
				action="<?php echo $pluginUrl;?>">
    					<?php
            $tpl->input("action", TPLH::EDITER_DPX, "hidden", "", $fedit);
            $tpl->input("id", $v->id, "hidden", "", $fedit);
            ?>
    					</form>
			<form method="post" id="<? echo $fdelete ?>"
				action="<?php echo $pluginUrl;?>">
    					<?php
            $tpl->input("action", TPLH::SUPPRIMER_DPX, "hidden", "", $fdelete);
            $tpl->input("id", $v->id, "hidden", "", $fdelete);
            ?>
    					</form>
			<ul
				class="<? echo ($i++%2) == 0 ? "ligne_claire_rub":"ligne_fonce_rub"?>">
				<li style="width: 770px;"><span><? echo $v->titre; ?></span></li>
				<li style="width: 176px;">
        					<?
            $tpl->submitLink("éditer", $fedit);
            ?>
        					<span> </span>
        					<?
            $tpl->submitLink("supprimer", $fdelete);
            ?>
        					</li>
			</ul>
            			     <?
        }
    } else {
        // aucun dpx
        ?><p>Aucun élément à afficher</p><?
    }
    
    ?>
        			</div>
        
        
        
        		<?php
} else {
    ?>
    			<div class="fonction_valider">
    				<?php $tpl->submitLink("ENREGISTRER LES MODIFICATIONS"); ?>
    			</div>
	</div>
	<table width="100%" cellspacing="0" cellpadding="5">
		<tbody>
			<tr class="claire">
				<td class="designation">Titre</td>
				<td>
						<? $tpl->input("titre", $dpx->titre, "text", "form_long"); ?>
						</td>
			</tr>
			<tr class="fonce">
				<td class="designation">Configuration</td>
				<td>
					<table width="100%" cellspacing="0" cellpadding="5">
						<thead>
							<tr>
								<th>Caracteristique</th>
								<th>Déclinaison</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
	<?php
    if ($isNew) {
        ?>
                						    <select name="caracteristique"
									form="<?php echo $tpl->formId;?>">
                								<?php $tpl->caracteristiqueSelectOption();?>
                							</select>
	    <?
    } else {
        $tpl->input("caracteristique", $dpx->caracteristique);
        $tpl->input("declinaison", $dpx->declinaison);
        
        $carac = new Caracteristiquedesc();
        $carac->charger_id($dpx->caracteristique);
        ?>
			    							<label><? echo $carac->titre;?></label>
	    <?
    }
    ?>
										</td>

								<td>
						<?php
    if ($isNew) {
        ?>
                    						<select name="declinaison"
									form="<?php echo $tpl->formId;?>">
                    							<? $tpl->declinaisonSelectOptions();?>
                    						</select>
						<?
    } else {
        $dec = new Declinaisondesc();
        $dec->charger_id($dpx->declinaison);
        ?>
										    <label><? echo $dec->titre;?></label>
    <?
    }
    ?>
										</td>
							</tr>
							<tr>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr class="claire">
				<td class="designation">Valeurs disponibles</td>
				<td>
                    		<?
    if ($action == TPLH::CREER_DPX) {
        $map = Dpxpdo::getInstance()->createDpxMap($dpx);
        $rowspan = count($map->declidisps) + 1;
        ?>
            		 <table id="dpx" width="100%" cellspacing="0"
						cellpadding="5">
						<thead>
							<tr>
								<th>Caracteristique</th>
								<th>Declinaison</th>
								<th>Prix</th>
								<th>Ref</th>
							</tr>
						</thead>
						<tbody>
                        		 <?
        $i = 0;
        $count = 0;
        foreach ($map->caracdisps as $v) {
            ?>
    						<tr class="<? echo ($i++%2) == 0 ? "claire":"fonce"?>">
								<td rowspan="<? echo $rowspan; ?>"><? echo $v->titre; ?></td>
							</tr>
                		     <?
            $j = $i;
            foreach ($v->items as $disp) {
                ?>
            		         <tr class="<? echo ($j++%2) == 0 ? "claire":"fonce"?>">
								<td><?
                $tpl->input("disp[$count][caracdisp]", $disp->caracdisp);
                $tpl->input("disp[$count][declidisp]", $disp->declidisp);
                echo $disp->titre;
                ?></td>
								<td><? $tpl->input("disp[$count][prix]", $disp->prix, "text"); ?></td>
								<td><? $tpl->input("disp[$count][ref]", $disp->ref, "text"); ?></td>
							</tr>
            		         <?
                $count ++;
            }
        }
        ?>
            		 	</tbody>
					</table>
                    		 <?
    }
    
    if ($action == TPLH::EDITER_DPX) {
        $map = Dpxpdo::getInstance()->getDpxMap($dpx);
        $rowspan = count($map->declidisps) + 1;
        ?>
            		 <table id="dpx" width="100%" cellspacing="0"
						cellpadding="5">
						<thead>
							<tr>
								<th>Caracteristique</th>
								<th>Declinaison</th>
								<th>Prix</th>
								<th>Ref</th>
							</tr>
						</thead>
						<tbody>
                        		 <?
        $i = 0;
        $count = 0;
        foreach ($map->caracdisps as $v) {
            ?>
                        		     <tr
								class="<? echo ($i++%2) == 0 ? "claire":"fonce"?>">
								<td rowspan="<? echo $rowspan; ?>"><? echo $v->titre; ?></td>
							</tr>
							<?
            $j = $i;
            foreach ($v->items as $disp) {
                $disp instanceof VODPXDisp;
                ?>
            		         <tr
								class="<? echo ($j++%2) == 0 ? "claire":"fonce"?>">
								<td>
								<?
                $tpl->input("disp[$count][id]", $disp->id);
                echo $disp->titre;
                ?>
                                </td>
								<td><? $tpl->input("disp[$count][prix]", $disp->prix, "text"); ?></td>
								<td><? $tpl->input("disp[$count][ref]", $disp->ref, "text"); ?></td>
							</tr>
                            	<?
                $count ++;
            }
        }
        ?>
            		 	</tbody>
					</table>
                    		 <?
    }
    ?>
            	</td>
			</tr>
		</tbody>
	</table>
<?php
}
?>
</div>