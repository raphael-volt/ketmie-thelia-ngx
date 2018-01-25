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

class Port extends Baseobj{

        var $id;
        var $zone;
        var $poids;
        var $port;

        const TABLE="port";
        var $table=self::TABLE;

        var $bddvars=array("id", "zone", "poids", "port");

        function __construct(){
                parent::__construct();
        }


        function charger($id){
                return $this->getVars("select * from $this->table where id=\"$id\"");
        }

        function calcule($zone, $poids){
                $this->getVars("select * from $this->table where zone='" . $zone . "' and poids>='" . $poids . "' order by port limit 0,1");
                return $this->port;

        }
}
?>