<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                            		 */
/*                                                                                   */
/*      Copyright (c) Octolys Development		                                     */
/*		email : thelia@octolys.fr		        	                             	 */
/*      web : http://www.octolys.fr						   							 */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 2 of the License, or            */
/*      (at your option) any later version.                                          */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program; if not, write to the Free Software                  */
/*      Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    */
/*                                                                                   */
/*************************************************************************************/

include_once(realpath(dirname(__FILE__)) . "/../../../classes/PluginsTransports.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Message.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Messagedesc.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Raisondesc.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Mail.class.php");

class Colissimo extends PluginsTransports{

	public function __construct(){
		parent::__construct("colissimo");
	}

	public function init() {

		$this->ajout_desc("Colissimo", "Colissimo", "", 1);

		$test = new Message();

		if (! $test->charger("colissimo")) {

			$message = new Message();
			$message->nom = "colissimo";
			$lastid = $message->add();

			$messagedesc = new Messagedesc();
			$messagedesc->message = $lastid;
			$messagedesc->lang = 1;
			$messagedesc->intitule = "Envoi du numéro de suivi Colissimo";
			$messagedesc->titre = "Expédition de votre commande __COMMANDE__";
			$messagedesc->descriptiontext =
				  "__RAISON__ __NOM__ __PRENOM__,\n\n"
				. "Nous vous remercions de votre commande sur notre site __URLSITE__.\n\n"
				. "Un colis concernant votre commande __COMMANDE__ du __DATE__ __HEURE__ a quitté nos entrepôts pour être pris en charge par La Poste le __DATEDJ__.\n\n"
				. "Son numéro de suivi est le suivant : __COLIS__\n\n"
				. "Il vous permet de suivre votre colis en ligne sur le site de La Poste : http://www.coliposte.net.\n"
				. "Il vous sera, par ailleurs, très utile si vous étiez absent au moment de la livraison de votre colis : en fournissant ce numéro de Colissimo Suivi, vous pourrez retirer votre colis dans le bureau de Poste le plus proche.\n\nATTENTION ! Si vous ne trouvez pas l'avis de passage normalement déposé dans votre boîte aux lettres au bout de 48 Heures jours ouvrables, n'hésitez pas à aller le réclamer à votre bureau de Poste, muni de votre numéro de Colissimo Suivi.\n\n"
				. "Nous restons à votre disposition pour toute information complémentaire.\n"
				. "Cordialement,\n"
				. "L'équipe __NOMSITE__.\n";

			$messagedesc->description = nl2br($messagedesc->descriptiontext);

			$messagedesc->add();
		}
	}

	public function destroy() {

		$message = new Message();

		if ($message->charger("colissimo")) {
			if (method_exists($message, 'supprimer'))
				$message->supprimer();
			else
				$message->delete();
		}
	}

	public function calcule(){

		if($this->poids<=0.5) return 6;
		else if($this->poids<=1) return 6.50;
		else if($this->poids<=2) return 7;
		else if($this->poids<=3) return 8;
		else if($this->poids<=5) return 9;
		else if($this->poids<=7) return 10;
		else if($this->poids<=10) return 12;
		else if($this->poids<=15) return 14;
		else if($this->poids<=30) return 20;
		else if($this->poids>30) return 20;
	}

	private function substitutions($texte, $client, $commande) {

		$datecommande = strtotime($commande->date);

		$raisondesc = new Raisondesc();
		$raisondesc->charger($client->raison, $commande->lang);

		$texte = str_replace("__RAISON__", $raisondesc->long, $texte);
		$texte = str_replace("__NOM__", $client->nom, $texte);
		$texte = str_replace("__PRENOM__", $client->prenom, $texte);

		$texte = str_replace("__URLSITE__", Variable::lire('urlsite'), $texte);
		$texte = str_replace("__NOMSITE__", Variable::lire('nomsite'), $texte);

		$texte = str_replace("__COMMANDE__", $commande->ref, $texte);
		$texte = str_replace("__DATE__", strftime("%d/%m/%Y", $datecommande), $texte);
		$texte = str_replace("__HEURE__", strftime("%H:%M:%S", $datecommande), $texte);
		$texte = str_replace("__DATEDJ__", strftime("%d/%m/%Y"), $texte);
		$texte = str_replace("__COLIS__", $commande->colis, $texte);

		return $texte;
	}

	public function statut($commande) {

		if ($commande->statut == "4") {

			if (! $commande->colis)
				return;

			$modTransport = new Modules();
			$modTransport->charger('colissimo');

			if($modTransport->id != $commande->transport)
				return;

			$message = new Message();
			$message->charger("colissimo");

			$messagedesc = new Messagedesc();
			$messagedesc->charger($message->id, $commande->lang);

			$client = new Client();
			$client->charger_id($commande->client);

			$sujet = $this->substitutions($messagedesc->titre, $client, $commande);
			$texte = $this->substitutions($messagedesc->descriptiontext, $client, $commande);
			$html  = $this->substitutions($messagedesc->description, $client, $commande);

			Mail::envoyer(
				"$client->prenom $client->nom", $client->email,
				Variable::lire('nomsite'), Variable::lire('emailcontact'),
				$sujet,
				$html, $texte);
		}
	}
}
?>