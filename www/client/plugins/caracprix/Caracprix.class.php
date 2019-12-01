<?php
include_once (realpath(dirname(__FILE__)) . "/../../../classes/PluginsClassiques.class.php");

class Caracprix extends PluginsClassiques
{
    public $id;
    public $titre;
    public $admin_titre;
    
    public function __construct()
    {
        parent::__construct("caracprix");
    }
    public function boucle($texte, $args)
    {
        $res = "";
        
        $test = lireTag($args, "test");
        if($test != "") {
            for($i=0; $i<3; $i++) {
                $temp = $texte;
                $temp = str_replace("#ID", $i, $temp);
                $temp = str_replace("#TITRE", "Test $i", $temp);
                $temp = str_replace("#PRODUIT", rand(1, 1000), $temp);
                $res .= $temp;
            }
        }
        return $res;
    }
}

class Caracprixdisp extends PluginsClassiques
{
    public $id;
    public $id_caracprix;
    public $titre;
    public $ref;
    public $prix;
    
    public function __construct()
    {
        parent::__construct("caracprixdisp");
    }
    public function boucle($texte, $args)
    {
        $res = "<!-- ??? -->";
        
        $test = lireTag($args, "test");
        $produit = lireTag($args, "produit");
        $caracprix = lireTag($args, "caracprix");
        
        if($test != "") {
            for($i=0; $i<3; $i++) {
                $temp = $texte;
                $temp = str_replace("#ID", $i, $temp);
                $temp = str_replace("#TITRE", "Type $i", $temp);
                $temp = str_replace("#PRIX", 10 + rand($i*10, $i*10+10), $temp);
                $temp = str_replace("#PRODUIT", $produit, $temp);
                $temp = str_replace("#CARACPRIX", $caracprix, $temp);
                
                $res .= $temp;
            }
        }
        return $res;
    }
}

