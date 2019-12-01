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

class RetraitAtelier extends PluginsTransports{

	public function __construct(){
		parent::__construct("retraitatelier");
	}

	public function init() {

		$this->ajout_desc("Retrait atelier", "Retraitatelier", "", 1);

		$test = new Message();

		if (! $test->charger("retraitatelier")) {

			$message = new Message();
			$message->nom = "retraitatelier";
			$lastid = $message->add();

			$messagedesc = new Messagedesc();
			$messagedesc->message = $lastid;
			$messagedesc->lang = 1;
			$messagedesc->intitule = "Votre comande est prête";
			$messagedesc->titre = "Retrait de votre commande __COMMANDE__";
			$messagedesc->descriptiontext =
				  "__RAISON__ __NOM__ __PRENOM__,\n\n"
				. "Nous vous remercions de votre commande sur notre site __URLSITE__.\n\n"
				. "Votre commande __COMMANDE__ du __DATE__ est disponible.\n\n"
				. "Vous pouvez retirer vos commande du lundi au vendredi de 9H à 18h.\n\n"
				. "Nous restons à votre disposition pour toute information complémentaire.\n"
				. "Cordialement,\n"
				. "L'équipe __NOMSITE__.\n";

			$messagedesc->description = nl2br($messagedesc->descriptiontext);

			$messagedesc->add();
		}
	}

	public function destroy() {

		$message = new Message();

		if ($message->charger("retraitatelier")) {
			if (method_exists($message, 'supprimer'))
				$message->supprimer();
			else
				$message->delete();
		}
	}

	public function calcule(){

		return 0;
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

		return $texte;
	}

	public function statut($commande) {

		if ($commande->statut == "4") {

			$modTransport = new Modules();
			$modTransport->charger('retraitatelier');

			if($modTransport->id != $commande->transport)
				return;

			$message = new Message();
			$message->charger("retraitatelier");

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