<?php 
class DPXAdminRenderer
{
    
    /**
     *
     * @var DPXService
     */
    private $service;
    
    function __construct(DPXService $service)
    {
        $this->service = $service;
    }
    
    private function _js() {
        ?>
<script>
function dpxSubmit(event, inputId, value, formId) {
    event.stopImmediatePropagation()
    event.preventDefault()
	var e = document.getElementById(inputId);
	if(!e)
		return
    e.setAttribute('value', value)
    console.log('set value=' + value + ' to ' + inputId)
    if (!formId)
        formId = 'form_modif'
	console.log(formId + ' to ' + inputId)            
    document.getElementById(formId).submit()
    return false
}
</script>
        <?php
    }
    private function _css()
    {
        ?>
<style>
.row {
	color: #3B4B5B;
	margin: 0px 0 0 0px;
	padding: 5px 7px 0 7px;
	border-left: 1px solid #C4CACE;
	border-right: 1px solid #C4CACE;
}

.claire {
	background-color: #EBEDEE;
}

.fonce {
	background-color: #D4DADD;
}

.dpxtab {
	
}

.dpxtab td, .dpxtab th {
	padding: 4px;
}

.main-tab {
	border-collapse: collapse;
	width: 100%;
}

.titre-2 {
	padding: 0;
	height: 30px;
	background-image: url(gfx/fond_cellule_entete.jpg);
	background-repeat: repeat-x;
	border-right: 1px solid #96A8B5;
	z-index: 10px;
}

.titre-2 h3 {
	margin: 0px 0 0 0px;
	height: 23px;
	color: #2F3D46;
	padding: 5px 0 0 7px;
}

.main-tab .view {
	width: 100%;
}

.controllers>p.links {
	display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: space-between;
    align-content: flex-start;
    align-items: flex-start;
}

.main-tab>tbody>tr>td {
	padding: 5px;
}

.links > a {
	order: 0;
    flex: 0 1 auto;
    align-self: auto;
}
.links > a {
    padding-left: 5px;
}
.links > a:nth-child(1) {
    padding-left: 0px;
}
</style>
<?php
    }

    function liste()
    {
        $this->_css();
        $srv = $this->service;
        $collection = $srv->getDPXCollection();
        $this->_entete(DPXAction::LIST, false);
        ?>
<div class="titre-2">
	<h3>Declinaisons disponibles</h3>
</div>
<table class="main-tab">
	<tbody>
	<?php
        $i = 0;
        foreach ($collection as $dpx) {
            ?>
	<tr class="row <?php echo ($i ++)%2==0 ? "claire":"fonce";?>">
			<td valign="top" class="controllers">
				<p class="links">
		<?php
            $ue = $srv->getActionUrl(DPXAction::EDITER, $dpx->declinaison);
            $us = $srv->getActionUrl(DPXAction::DESACTIVER, $dpx->declinaison);
            if ($dpx->active) {
                ?>
				<a href="<?php echo $us;?>">désactiver</a>
				<a href="<?php echo $ue;?>">éditer</a>
				<?php
            } else {
                ?>
            	</a><a href="<?php echo $ue;?>">activer</a><a class="fake-link"></a>
                <?php
            }
            ?>
            	<a title="Voir la rubrique en ligne" href="declinaison_modifier.php?id=<?php echo $dpx->declinaison;?>">
					<img src="gfx/site.png" alt="Voir la déclinaison" title="Voir la déclinaison">
				</a>
            </p>
				<p><?php echo $dpx->titre;?></p>
			</td>
			<td class="view">
				<table class="dpxtab">
					<tr>
						<th>Titre</th>
						<th>Ref</th>
						<th>Prix</th>
					</tr>
            	<?php
            foreach ($dpx->items as $desc) {
                ?>
                <tr>
						<td><?php echo $desc->titre;?></td>
						<td><?php echo $desc->ref;?></td>
						<td><?php echo $desc->prix;?></td>
					</tr>
            	<?php
            }
            ?>
            </table>
			</td>
		</tr>
<?php
        }
        ?>
</tbody>
</table>
<?php
    }

    private $_adminEditerFlag=false;
    function adminDecliprix($id_declinaison)
    {
        if($this->_adminEditerFlag)
            return;
        $this->_adminEditerFlag = true;
        $srv = $this->service;
        $dpx = $srv->getDpxByDeclination($id_declinaison);
        $this->_css();
        ?>
<div id="dpx-admin-editer">
<form method="post" action="<?php echo $srv->getPluginUrl();?>" 
	id="<?php echo DPXAction::FORM_ID; ?>">
        <?php
        $this->_entete(DPXAction::EDITER);
        $this->_input(DPXAction::PARAM_ACTION, DPXAction::MAJ_DECLIPRIX);
        $this->_input(DPXAction::PARAM_ID_DECLINATION, $id_declinaison);
        ?>
    <table width="100%" cellspacing="0" cellpadding="5">
		<tr class="fonce">
			<td class="designation"><?php echo $dpx->titre; ?></td>
		</tr>
	</table>
        <?php
        $this->_formControllers($dpx);
        ?>
    </form>
</div>
<?php
    }

    function adminDeclinaison($id_declinaison)
    {
        $srv = $this->service;
        $actif = $srv->hasPdx($id_declinaison);
        $this->_css();
        ?>
	<div class="entete_liste_config">
		<div class="titre">DÉCLINAISON PAR PRIX</div>
    	<?php
	    $this->_declinaisonActionButtons($actif, $id_declinaison);
        ?>
    </div>
    	<?php
    	if($actif)
            $this->_formControllers($srv->getDpxByDeclination($id_declinaison));
            else {
             ?>
             <table style="width: 100%;"></table>

             <?php 
            }
    }

    private function _entete($titre, $submitable = true)
    {
        $service = $this->service;
        ?>
<p align="left">
	<span class="lien04"> <a href="accueil.php" class="lien04">
			<?php echo trad('Accueil', 'admin'); ?>
		</a>
	</span> <img src="gfx/suivant.gif" width="12" height="9" border="0" />
	<a href="module_liste.php" class="lien04">
		<?php echo trad('Modules', 'admin'); ?>
	</a> <img src="gfx/suivant.gif" width="12" height="9" border="0" /> <a
		href="<?php echo $service->getPluginUrl(); ?>" class="lien04">
		<?php echo $service->getPluginNom();?>
	</a>
</p>

<div class="entete_liste_config">
	<div class="titre"><?php echo $service->getPluginNom() . "[$titre]"?></div>
	<?php
        if ($submitable) {
            $this->_dpxActionButtons();
        }
        ?>
</div>
<?php
    }

    private function _declinaisonActionButtons($actif, $id_decli="")
    {
        $this->_js();
        $this->_input(DPXAction::PARAM_ID_DECLINATION, 1);
        if($actif) {
        ?>
<div class="fonction_valider">
	<a href="" onclick="dpxSubmit(event, 'zoneaction', '<?php echo DPXAction::DESACTIVER;?>')">
		DÉSACTIVER</a>
	<a href="" onclick="dpxSubmit(event, 'zoneaction', '<?php echo DPXAction::MAJ_DECLIPRIX;?>')">
		VALIDER LES MODIFICATIONS </a>
</div>
<?php
        } else {
?>
<div class="fonction_valider">
	<a href="" 
	onclick="dpxSubmit(event, 'zoneaction', '<?php echo DPXAction::ACTIVER;?>')">
		ACTIVER</a>
</div>
<?php 
        }
    }
    
    private function _dpxActionButtons()
    {
        ?>
<div class="fonction_valider">
	<a href=""
		onclick="document.getElementById('<?php echo DPXAction::FORM_ID;?>').submit()">
		VALIDER LES MODIFICATIONS </a>
</div>
<?php
    }

    private function _input($name, $value, $type = "hidden")
    {
        echo <<<EOT
<input name="$name" value="$value" type="$type">
EOT;
    }

    private function _formControllers(DPX $dpx, $redirect = false)
    {
        if ($redirect)
            $this->_input(DPXAction::PARAM_REDIRECT, $redirect);
        ?>
<table style="width: 100%; margin-bottom: 10px" class="fonce">
	<tr class="fonce">
		<th>Titre</th>
		<th>Ref</th>
		<th>Prix</th>
	</tr>
    	<?php
        $i = 0;
        $name = $this->service->getPluginNom();
        foreach ($dpx->items as $desc) {
            $cls = ($i % 2) == 0 ? "fonce" : "claire";
            ?>
        <tr class="<?php echo $cls;?>">
            <?php
            $this->_input("{$name}[$i][id]", $desc->id);
            $this->_input("{$name}[$i][declidisp]", $desc->declidisp);
            ?>
            <td><?php echo $desc->titre; ?></td>
		<td>
    	        <?php $this->_input("{$name}[$i][ref]", $desc->ref, "text");?>
            </td>
		<td>
				<?php $this->_input("{$name}[$i][prix]", $desc->prix, "text");?>
            </td>
	</tr>
        <?php
            $i ++;
        }
        ?>
    </table>
<?php
    }
}
?>