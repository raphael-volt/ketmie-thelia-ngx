<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*		email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/
require_once __DIR__ . "/../fonctions/autoload.php";

class Client extends Baseobj{

        var $id;
        var $ref;
        var $datecrea;
        var $raison;
        var $entreprise;
        var $siret;
        var $intracom;
        var $nom;
        var $prenom;
        var $telfixe;
        var $telport;
        var $email;
        var $motdepasse;
        var $adresse1;
        var $adresse2;
        var $adresse3;
        var $cpostal;
        var $ville;
        var $pays;
        var $parrain;
        var $type;
        var $pourcentage;
        var $lang;

        const TABLE="client";
        var $table=self::TABLE;

        var $bddvars = array("id", "ref", "datecrea", "raison", "entreprise", "siret", "intracom", "nom", "prenom", "telfixe", "telport", "email", "motdepasse", "adresse1", "adresse2", "adresse3", "cpostal", "ville", "pays", "parrain", "type", "pourcentage", "lang");

        function __construct($id = 0){
                parent::__construct();

                if($id > 0)
                  $this->charger_id($id);
        }

    function add()
    {
        $this->datecrea = date('Y-m-d H:i:s');
        $this->id = parent::add();
        $this->ref = date("ymdHi") . genid($this->id, 6);
        $this->maj();
        return $this->id;
    }

        function charger($email, $motdepasse){
                $query = sprintf("select * from $this->table where email='%s' and motdepasse=PASSWORD('%s')",
                $this->escape_string($email),
                $this->escape_string($motdepasse));

                return $this->getVars($query);
        }

        function charger_mail($email){
                return $this->getVars("select * from $this->table where email=\"$email\"");
        }

        function existe($email){
                $query = "select * from $this->table where email=\"$email\"";
                $resul = $this->query($query);
                return $this->num_rows($resul);

        }

        function crypter(){
                $query = "select PASSWORD('$this->motdepasse') as resultat";
                $resul = $this->query($query);
                $this->motdepasse = $this->get_result($resul, 0, "resultat");

        }

        function charger_ref($ref){
                return $this->getVars("select * from $this->table where ref=\"$ref\"");
        }

        function acommande(){
                $commande = new Commande();
                $query = "select * from $commande->table where statut>1 and statut<>5 and client=\"" . $this->id . "\"";
                $resul = $commande->query($query);
                if($commande->num_rows($resul))
                        return 1;
                else
                        return 0;

        }

        function nbcommandes(){
                $commande = new Commande();
    $query = "select * from $commande->table where statut>1 and statut<>5 and client=\"" . $this->id . "\" and statut<>5";
                $resul = $commande->query($query);
                return $commande->num_rows($resul);
        }
}

?>