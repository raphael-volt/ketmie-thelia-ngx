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

	class Adresse extends Baseobj{

		var $id;
		var $libelle;
		var $client;
		var $raison;
		var $entreprise;
		var $nom;
		var $prenom;
		var $adresse1;
		var $adresse2;
		var $adresse3;
		var $cpostal;
		var $ville;
		var $tel;
		var $pays;

		const TABLE="adresse";
		var $table=self::TABLE;

		var $bddvars = array("id", "libelle", "client", "raison", "entreprise", "nom", "prenom", "adresse1", "adresse2", "adresse3", "cpostal", "ville", "tel", "pays");

		function __construct($id = 0){
			parent::__construct();

			 if($id > 0)
 			  $this->charger($id);
		}

		function charger($id){
			return $this->getVars("select * from $this->table where id=\"$id\"");

		}


	}


?>