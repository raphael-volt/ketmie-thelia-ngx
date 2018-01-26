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

class Cache extends Baseobj{

        var $id;
        var $session;
        var $texte;
        var $args;
        var $variables;
        var $type_boucle;
        var $res;
        var $date;

        const TABLE="cache";
        var $table=self::TABLE;

        var $bddvars=array("id", "session", "texte", "args", "variables", "type_boucle", "res", "date");

        function __construct(){
                parent::__construct();
        }

        function charger($texte, $args, $variables, $type_boucle){
                return $this->getVars("select * from $this->table where texte=\"$texte\" and args=\"$args\" and variables=\"$variables\" and type_boucle=\"$type_boucle\"");
        }

        function charger_session($session, $texte, $args, $variables, $type_boucle){
                return $this->getVars("select * from $this->table where session=\"$session\" and texte=\"$texte\" and args=\"$args\" and variables=\"$variables\" and type_boucle=\"$type_boucle\"");
        }


        function vider($type_boucle, $variables){
                $query = "delete from $this->table where type_boucle=\"$type_boucle\" and variables like \"$variables\"";
                $resul = $this->query($query);
        }

        function vider_session($session, $type_boucle, $variables){
                $query = "delete from $this->table where session=\"$session\" and type_boucle=\"$type_boucle\" and variables like \"$variables\"";
                $resul = $this->query($query);
        }

}

?>