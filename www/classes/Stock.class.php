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

class Stock extends Baseobj{

        var $id;
        var $declidisp;
        var $produit;
        var $valeur;
        var $surplus;

        const TABLE="stock";
        var $table=self::TABLE;

        var $bddvars=array("id", "declidisp", "produit", "valeur", "surplus");

        function __construct($declidisp = 0, $produit = 0){
                parent::__construct();

                if($declidisp > 0 && $produit > 0)
                  $this->charger($declidisp, $produit);
        }

        function charger($declidisp, $produit){
                return $this->getVars("select * from $this->table where declidisp=\"$declidisp\" and produit=\"$produit\"");
        }
}
?>