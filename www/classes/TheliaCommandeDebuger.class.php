<?php

class TheliaCommandeDebuger
{
	
	public function resetSession()
	{
		// deconnexion();
		$_SESSION['navig'] = new Navigation();
	}
	
	public function setupTest()
	{
		Filtretarifbo::clearLog();
		PDOThelia::getInstance()->query("DELETE FROM commande WHERE 1;
DELETE FROM venteadr WHERE 1;
DELETE FROM ventedeclidisp WHERE 1;
DELETE FROM venteprod WHERE 1;");
		
		$client = Filtretarifbo::getDebugClient();
		$nav = new Navigation();
		$nav->client = $client;
		$nav->connecte = 1;
		
		
		$panier = new Panier();
		$perso = new Perso();
		$perso->declinaison = 5;
		$perso->valeur = 19;
		$ref = "BJX-BO-0063";
		$panier->ajouter($ref, 1, array($perso));
		
		$ref = "CLG-0002";
		$panier->ajouter($ref, 1);
		
		$ref = "BJX-TOR-0120";
		$panier->ajouter($ref, 1);
		
		$nav->panier = $panier;
		
		$_SESSION['navig'] = $nav;
		header("Location: " . urlfond("adresse"));
		
		//ActionsModules::instance()->appel_module("apresconnexion", $client);
		//return $panier->tabarticle;
	}
}