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

class Reecriture extends Baseobj{

        var $id;
        var $fond;
        var $url;
        var $param;
        var $lang;
        var $actif;

        const TABLE="reecriture";
        var $table=self::TABLE;

        var $bddvars = array("id", "fond", "url", "param", "lang", "actif");

        function __construct($url=""){
                parent::__construct();

                if($url != "")
                  $this->charger($url);
        }

        function charger($url){
                return $this->getVars("select * from $this->table where url=\"$url\" ORDER BY actif DESC");
        }

        function charger_param($fond, $param, $lang=1, $actif=1){
             return $this->getVars("select * from $this->table where fond=\"$fond\" and param=\"$param\" and lang=\"$lang\" and actif=\"$actif\"");
        }

        function charger_url_classique($param, $lang, $actif){
                 preg_match('/fond=([^&]*)(.*)$/', $param, $rec);

             return $this->getVars("select * from $this->table where fond=\"" . $rec[1] . "\" and param=\"" . $rec[2] ."\" and lang=\"$lang\" and actif=\"$actif\"");
        }
}
?>