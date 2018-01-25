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

class Caracval extends Baseobj{

        var $id;
        var $produit;
        var $caracteristique;
        var $caracdisp;
        var $valeur;

        const TABLE="caracval";
        var $table=self::TABLE;

        var $bddvars = array("id", "produit", "caracteristique", "caracdisp", "valeur");

        function __construct($produit = 0, $caracteristique = 0){
                parent::__construct();

                if($produit > 0 && $caracteristique > 0)
                        $this->charger($produit, $caracteristique);	
        }

        function charger($produit, $caracteristique){
                return $this->getVars("select * from $this->table where produit=\"$produit\" and caracteristique=\"$caracteristique\"");
        }

        function charger_caracdisp($produit, $caracteristique, $caracdisp){
                return $this->getVars("select * from $this->table where produit=\"$produit\" and caracteristique=\"$caracteristique\" and caracdisp=\"$caracdisp\"");


        }

        function charger_valeur($valeur){
                return $this->getVars("select * from $this->table where valeur=\"$valeur\"");


        }

}

?>
