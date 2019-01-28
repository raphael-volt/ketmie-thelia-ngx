<?php
/*************************************************************************************/
/* */
/* Thelia */
/* */
/* Copyright (c) OpenStudio */
/* email : info@thelia.net */
/* web : http://www.thelia.net */
/* */
/* This program is free software; you can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License */
/* */
/* This program is distributed in the hope that it will be useful, */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the */
/* GNU General Public License for more details. */
/* */
/* You should have received a copy of the GNU General Public License */
/* along with this program. If not, see <http://www.gnu.org/licenses/>. */
/* */
/**
 * **********************************************************************************
 */
require_once __DIR__ . "/../fonctions/autoload.php";

class Administrateur extends Baseobj
{

    var $id;

    var $identifiant;

    var $motdepasse;

    var $prenom;

    var $nom;

    var $profil;

    var $lang;

    var $autorisation;

    const TABLE = "administrateur";

    var $table = self::TABLE;

    var $bddvars = array(
        "id",
        "identifiant",
        "motdepasse",
        "prenom",
        "nom",
        "profil",
        "lang"
    );

    function __construct($id = 0)
    {
        parent::__construct();
        
        if ($id > 0)
            $this->charger_id($id);
    }
    
    function charger($identifiant, $motdepasse=false) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("SELECT id FROM administrateur WHERE motdepasse = PASSWORD(?) AND identifiant=?");
        $stmt->bindParam(1, $motdepasse);
        $stmt->bindParam(2, $identifiant);
        $stmt->execute();
        if($stmt->rowCount()) {
            $id = $stmt->fetchColumn(0);
            $this->charger_id($id);
            return 1;
        }
        return 0;
    }
    function charger_id($id)
    {
        if (parent::charger_id($id)) {
            $this->autorisation();
            return 1;
        } else {
            return 0;
        }
    }

    function autorisation()
    {
        $autorisation_administrateur = new Autorisation_administrateur();
        $query = "select * from $autorisation_administrateur->table where administrateur=\"" . $this->id . "\"";
        $resul = $autorisation_administrateur->query($query);
        
        while ($resul && $row = $this->fetch_object($resul)) {
            $autorisation = new Autorisation();
            $autorisation->charger_id($row->autorisation);
            $temp_auth = new Autorisation_administrateur();
            $temp_auth->id = $row->id;
            $temp_auth->administrateur = $row->administrateur;
            $temp_auth->autorisation = $row->autorisation;
            $temp_auth->lecture = $row->lecture;
            $temp_auth->ecriture = $row->ecriture;
            
            $this->autorisation[$autorisation->nom] = new Autorisation_administrateur();
            $this->autorisation[$autorisation->nom] = $temp_auth;
        }
    }

    function crypter()
    {
        $query = "select PASSWORD('$this->motdepasse') as resultat";
        $resul = $this->query($query);
        $this->motdepasse = $this->get_result($resul, 0, "resultat");
    }
}

?>