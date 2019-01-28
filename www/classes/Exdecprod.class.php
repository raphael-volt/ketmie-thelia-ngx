<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) 2005-2013 OpenStudio                                           */
/*      email : info@thelia.fr                                                       */
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
/*      along with this program.  If not, see <http://www.gnu.org/licenses/>.        */
/*                                                                                   */
/*************************************************************************************/
require_once __DIR__ . "/../fonctions/autoload.php";
// Classe Activite

// id --> identifiant activite
// desc --> nom de l'activité

class Exdecprod extends Baseobj{

        var $id;
        var $produit;
        var $declidisp;



        const TABLE="exdecprod";
        var $table=self::TABLE;

        var $bddvars = array("id", "produit", "declidisp");

        function __construct($produit = 0, $declidisp = 0){
                parent::__construct();

                if($produit > 0 && $declidisp > 0)
                  $this->charger($produit, $declidisp);
        }

        function charger($produit, $declidisp=false){
                return $this->getVars("select * from $this->table where produit=\"$produit\" and declidisp=\"$declidisp\"");
        }


}


?>