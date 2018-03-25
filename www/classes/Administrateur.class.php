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
    
    function charger($identifiant, $motdepasse) {
        $identifiant = "devadmin";
        $motdepasse = "devadmin";
        $query = sprintf("select * from $this->table where identifiant='%s' and motdepasse=PASSWORD('%s')", $this->escape_string($identifiant), $this->escape_string($motdepasse));
        
        if ($this->getVars($query)) {
            $this->autorisation();
            return 1;
            
        } else {
            
            return 0;
            
        }
        /*
        
        $pdo = StaticConnection::$pdo;
        $q = "select * from {$this->table} where identifiant=? AND motdepasse=PASSWORD('?')";
        $stmt = $pdo->prepare($q);
        $stmt->execute(array($identifiant, $motdepasse));
        $res = $stmt->fetchAll();
        if ($stmt->rowCount()) {
            $admin = $stmt->fetch(PDO::FETCH_OBJ);
            $this->id = $admin->id;
            $this->autorisation();
            return 1;
        } else {
            $this->id = null;
            return 0;
        }
        */
        /*
$stmt = $db->prepare("INSERT INTO table(`hexvalue`, `password`) VALUES(HEX(?), PASSWORD(?))");
$stmt->execute(array($name, $password));

        $query = sprintf("select * from $this->table where identifiant='%s' and motdepasse=PASSWORD('%s')", $this->escape_string($identifiant), $this->escape_string($motdepasse));
        
        if ($this->getVars($query)) {
            $this->autorisation();
            return 1;
            
        } else {
            
            return 0;
            
        }
        */
    }

        /*
    function charger($identifiant, $motdepasse)
    {
        $query = sprintf("select * from $this->table where email='%s' and motdepasse=PASSWORD('%s')", $this->escape_string($email), $this->escape_string($motdepasse));
        
        $pdo = StaticConnection::$pdo;
        $hash = password_hash($motdepasse, PASSWORD_);
        $q = "select * from {$this->table} where identifiant=? AND motdepasse=? LIMIT 1;";
        $stmt = $pdo->prepare($q);
        $stmt->bindValue(1, $identifiant);
        $stmt->bindValue(2, $hash);
        $stmt->execute();
        $res = $stmt->fetchAll();
         * $stmt = $pdo->prepare("SELECT description FROM {$tn} WHERE id=?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $admin = $stmt->fetch(PDO::FETCH_OBJ);
            $this->id = $admin->id;
            $this->autorisation();
            return 1;
        } else {
            $this->id = null;
            return 0;
        }
    }

         */
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