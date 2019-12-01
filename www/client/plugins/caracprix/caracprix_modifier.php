<?php 
$module = new Caracprix();
?>
			<div id="contenu_int">
			   <p align="left">
				   	<a href="accueil.php" class="lien04"><?php echo trad('Accueil', 'admin'); ?> </a>
				   	<img src="gfx/suivant.gif" width="12" height="9" border="0" />
				   	<a href="configuration.php" class="lien04"><?php echo trad('Configuration', 'admin'); ?></a>
				   	<img src="gfx/suivant.gif" width="12" height="9" border="0" />
				   	<a href="plugins.php" class="lien04"><?php echo trad('Gestion_plugins', 'admin'); ?></a>
				   	<img src="gfx/suivant.gif" width="12" height="9" border="0" />
				   	<?php echo ActionsAdminModules::instance()->lire_titre_module($module); ?>
			    </p>

				<div id="bloc_description">
					<div class="entete_liste_config">
						<div class="titre"><?php echo trad('DESCRIPTION DU PLUGIN', 'admin'); ?></div>
						<div class="fonction_valider"><a href="#" onclick="document.getElementById('formulaire').submit()"><?php echo trad('VALIDER_LES_MODIFICATIONS', 'admin'); ?></a></div>
					</div>

					<form action="<?php echo($_SERVER['PHP_SELF']); ?>" id="formulaire" method="post">

					  	<input type="hidden" name="action" value="modifier" />
						<input type="hidden" name="nom" value="<?php echo($module->nom); ?>" />
						<input type="hidden" name="id" value="<?php echo($moduledesc->id); ?>" />
						<input type="hidden" name="lang" value="<?php echo($lang); ?>" />

						<!-- bloc descriptif de la rubrique -->
						<table width="100%" cellpadding="5" cellspacing="0">

							<?php if($module->id != ""){?>
						    <tr class="claire">
						        <th class="designation"><?php echo trad('Changer_langue', 'admin'); ?></th>
						        <th>
						        <?php
								foreach($langues as $langl) {
								?>
								<div class="flag<?php if($lang ==  $langl->id) { ?>Selected<?php } ?>">
									<a href="<?php echo($_SERVER['PHP_SELF']); ?>?nom=<?php echo $module->nom; ?>&id=<?php echo($id); ?>&lang=<?php echo($langl->id); ?>">
										<img src="gfx/lang<?php echo($langl->id); ?>.gif" />
									</a>
								</div>
								<?php } ?>
								</th>
						   	</tr>
							<?php } ?>

						   	<tr class="fonce">
						        <td class="designation"><?php echo trad('Titre', 'admin'); ?></td>
						        <td><input name="titre" id="titre" type="text" class="form_long" value="<?php echo htmlspecialchars($moduledesc->titre); ?>"/></td>
						   	</tr>

						   	<tr class="claire">
						        <td class="designation"><?php echo trad('Chapo', 'admin'); ?><br /><span class="note"><?php echo trad('courte_descript_intro', 'admin'); ?></span></td>
						        <td> <textarea name="chapo" id="chapo" cols="40" rows="2" class="form_long"><?php echo($moduledesc->chapo); ?></textarea></td>
						   	</tr>

						   	<tr class="fonce<?php echo ($module->type != Modules::PAIEMENT) ? 'bottom' : '' ?>">
						        <td class="designation"><?php echo trad('Description', 'admin'); ?><br /><span class="note"><?php echo trad('description_complete', 'admin'); ?></span></td>
						        <td><textarea name="description" id="description" cols="40" rows="2" class="form"><?php echo($moduledesc->description); ?></textarea></td>
						   	</tr>
						</table>
					</form>
				</div>
			</div>