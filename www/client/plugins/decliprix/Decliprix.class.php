<?php
include_once (realpath(dirname(__FILE__)) . "/../../../classes/PluginsClassiques.class.php");
require_once (realpath(dirname(__FILE__)) . '/core/DPXService.php');

class Decliprix extends PluginsClassiques
{

    /*
     * plugin definition
     */
    public $id;

    public $id_declidisp;

    public $prix;

    public $ref;

    public $etat;

    const TABLE = 'decliprix';

    public $table = self::TABLE;

    public $bddvars = array(
        "id",
        "id_declidisp",
        "prix",
        "ref",
        "etat"
    );

    /*
     * privates
     */
    
    /**
     *
     * @var DPXService
     */
    public $service;

    public function __construct()
    {
        parent::__construct("decliprixs");
        $this->service = new DPXService($this);
    }

    public function chargerDeclidisp($declidisp)
    {
        return $this->getVars("select * from $this->table where id_declidisp=\"$declidisp\"");
    }

    public function charger($id, $lang = null)
    {
        return $this->getVars("select * from $this->table where id=\"$id\"");
    }

    public function valider($id)
    {
        return $this->query("UPDATE $this->table SET etat=1 WHERE id=$id");
    }

    public function init()
    {
        $q = "CREATE TABLE IF NOT EXISTS `" . self::TABLE . "` (
			  `id` int(11) NOT NULL auto_increment,
			  `id_declidisp` int(11) NOT NULL,
			  `prix` DECIMAL(8,2) DEFAULT 0.00,
			  `ref` text NOT NULL,
			  `etat` tinyint(4) NOT NULL,
			  PRIMARY KEY  (`id`)
			) AUTO_INCREMENT=1 ;";
        
        $this->query($q);
        $q = "

CREATE TABLE IF NOT EXISTS rubdecliprix (
  id int(11) NOT NULL AUTO_INCREMENT,
  rubrique int(11) NOT NULL DEFAULT '0',
  decliprix int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY rubrique (rubrique,decliprix),
  KEY decliprix (decliprix)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;";
    }

    public function destroy()
    {
        $t = $this;
    }

    public function boucle($texte, $args)
    {
        $search = $order = $res = "";
        
        // récupération des arguments
        
        $id_declidisp = lireTag($args, "declidisp");
        $prix = lireTag($args, "prix");
        $ref = lireTag($args, "ref");
        $declinaison = lireTag($args, "declinaison");
        $produit = lireTag($args, "produit");
        $active = lireTag($args, "active");
        $index = lireTag($args, "index") != "";
        
        $showStock = false;
        $prices = null;
        $items = null;
        
        $missinProd = false;
        if ($produit != "" && $declinaison != "") {
            $q = "SELECT declidispdesc.titre, declidispdesc.declidisp,
decliprix.prix, decliprix.ref, stock.valeur as stock
FROM declidispdesc
LEFT JOIN stock ON declidispdesc.declidisp = stock.declidisp
LEFT JOIN declidisp ON declidisp.id = declidispdesc.declidisp
LEFT JOIN decliprix ON declidispdesc.declidisp = decliprix.id_declidisp
WHERE decliprix.id IS NOT NULL AND declidisp.declinaison=? AND stock.produit=?
ORDER BY declidispdesc.classement";
            $items = $this->prepare($q);
            $items->bindParam(1, $declinaison);
            $items->bindParam(2, $produit);
            $items->execute();
            if (! $items->rowCount())
                return "";
            $showStock = true;
        } else {
            if ($declinaison != "") {
                $missinProd = true;
                $q = "SELECT declidispdesc.titre, declidispdesc.declidisp,
    decliprix.prix, decliprix.ref, declidisp.declinaison
    FROM declidispdesc
    LEFT JOIN declidisp ON declidisp.id = declidispdesc.declidisp
    LEFT JOIN decliprix ON declidispdesc.declidisp = decliprix.id_declidisp
    WHERE decliprix.id IS NOT NULL AND declidisp.declinaison=?
    ORDER BY declidispdesc.classement";
                $items = $this->prepare($q);
                $items->bindParam(1, $declinaison);
                $items->execute();
                if (! $items->rowCount())
                    return "";
            }
        }
        if ($items) {
            $items = $items->fetchAll(PDO::FETCH_OBJ);
            $i = 0;
            if($missinProd)
                $produit = $items[0]->produit;
            foreach ($items as $value) {
                $a = $value->declidisp == $active ? $active:"";
                $res .= $this->pregDecliBoucle($texte, $value, true, $i++, $index, $declinaison, $produit);
            }
            return $res;
        }
        
        if ($id_declidisp != "") {
            $search .= " and id_declidisp=\"$id_declidisp\"";
        }
        if ($prix != "") {
            $search .= " and prix=\"$prix\"";
        }
        $query_decliprixs = "select * FROM $this->table where 1 $search $order";
        $items = $this->query($query_decliprixs)->fetchAll(PDO::FETCH_OBJ);
        foreach ($items as $i) {
            $temp = $texte;
            $temp = str_replace("#PRIX", $i->prix, $temp);
            $temp = str_replace("#REF", $i->ref, $temp);
            $temp = str_replace("#DECLIDISP", $i->id_declidisp, $temp);
            $temp = str_replace("#ACTIVE", $active, $temp);
            $res .= $temp;
        }
        
        return $res;
    }
    private function pregDecliBoucle($texte, $row, $replaceStock, $index, $showIndex, $decli, $prod)
    {
        $temp = $texte;
        
        $temp = str_replace("#PRIX", $row->prix, $temp);
        $temp = str_replace("#REF", $row->ref, $temp);
        $temp = str_replace("#TITRE", $row->titre, $temp);
        $temp = str_replace("#DECLIDISP", $row->declidisp, $temp);
        if ($replaceStock)
            $temp = str_replace("#STOCK", $row->stock, $temp);
        if($showIndex)
            $temp = str_replace("#INDEX", $index, $temp);  
            
        $temp = str_replace("#DECLINAISON", $decli, $temp);
        $temp = str_replace("#PRODUIT", $prod, $temp);    
        return $temp;
    }
    
    private function _pregDecliBoucle($texte, $row, $replaceStock, $min = false, $max = false)
    {
        $temp = $texte;
        
        $temp = str_replace("#PRIX", $row->prix, $temp);
        $temp = str_replace("#REF", $row->ref, $temp);
        $temp = str_replace("#TITRE", $row->titre, $temp);
        $temp = str_replace("#DECLIDISP", $row->declidisp, $temp);
        if ($replaceStock)
            $temp = str_replace("#STOCK", $row->stock, $temp);
        $p = floatval($row->prix);
        if ($min !== false && $p == $min) {
            $temp = str_replace("#PXMIN", $row->prix, $temp);
        } else
            $temp = str_replace("#PXMIN", "", $temp);
        if ($max !== false && $p == $max) {
            $temp = str_replace("#PXMAX", $row->prix, $temp);
        } else
            $temp = str_replace("#PXMAX", "", $temp);
        
        return $temp;
    }

    public function _boucle($texte, $args)
    {
        $search = $order = $res = "";
        
        // récupération des arguments
        
        $id_declidisp = lireTag($args, "declidisp");
        $prix = lireTag($args, "prix");
        $ref = lireTag($args, "ref");
        $noboucle = lireTag($args, "noboucle");
        $declinaison = lireTag($args, "declinaison");
        $produit = lireTag($args, "produit");
        $prixmin = lireTag($args, "prixmin");
        $prixmax = lireTag($args, "prixmax");
        $showStock = false;
        $prices = null;
        $items = null;
        if ($produit != "" && $declinaison != "") {
            $q = "SELECT declidispdesc.titre, declidispdesc.declidisp,
decliprix.prix, decliprix.ref, stock.valeur as stock
FROM declidispdesc
LEFT JOIN stock ON declidispdesc.declidisp = stock.declidisp
LEFT JOIN declidisp ON declidisp.id = declidispdesc.declidisp
LEFT JOIN decliprix ON declidispdesc.declidisp = decliprix.id_declidisp
WHERE decliprix.id IS NOT NULL AND declidisp.declinaison=? AND stock.produit=?
ORDER BY declidispdesc.classement";
            $items = $this->prepare($q);
            $items->bindParam(1, $declinaison);
            $items->bindParam(2, $produit);
            $items->execute();
            if (! $items->rowCount())
                return "";
            $showStock = true;
        } else {
            if ($declinaison != "") {
                $q = "SELECT declidispdesc.titre, declidispdesc.declidisp,
    decliprix.prix, decliprix.ref
    FROM declidispdesc
    LEFT JOIN declidisp ON declidisp.id = declidispdesc.declidisp
    LEFT JOIN decliprix ON declidispdesc.declidisp = decliprix.id_declidisp
    WHERE decliprix.id IS NOT NULL AND declidisp.declinaison=?
    ORDER BY declidispdesc.classement";
                $items = $this->prepare($q);
                $items->bindParam(1, $declinaison);
                $items->execute();
                if (! $items->rowCount())
                    return "";
            }
        }
        if ($items) {
            $items = $items->fetchAll(PDO::FETCH_OBJ);
            $min = false;
            $max = false;
            if ($prixmax != "" || $prixmin != "") {
                $prices = array();
                foreach ($items as $value) {
                    $prices[] = floatval($value->prix);
                }
                sort($prices, SORT_NUMERIC);
                $n = count($prices);
                if ($n) {
                    $min = $prices[0];
                    $max = $prices[$n - 1];
                }
            }
            foreach ($items as $value) {
                $res .= $this->pregDecliBoucle($texte, $value, true, $min, $max);
            }
            return $res;
        }
        
        if ($id_declidisp != "")
            $search .= " and id_declidisp=\"$id_declidisp\"";
        if ($prix != "")
            $search .= " and prix=\"$prix\"";
        $query_decliprixs = "select * FROM $this->table where 1 $search $order";
        $items = $this->query($query_decliprixs)->fetchAll(PDO::FETCH_OBJ);
        foreach ($items as $i) {
            $temp = $texte;
            
            $temp = str_replace("#PRIX", $i->prix, $temp);
            $temp = str_replace("#REF", $i->ref, $temp);
            $temp = str_replace("#DECLIDISP", $i->id_declidisp, $temp);
            $res .= $temp;
        }
        
        return $res;
    }

    public function action()
    {
        $action = $_POST['action'];
        switch ($action) {
            case 'ajdecliprix':
                $this->ajdecliprix();
                break;
            case 'suppdeclinaison':
                
                break;
            case 'suppdeclidisp':
                
                break;
            case 'ajdeclidisp':
                
                break;
        }
    }

    function suppdeclidisp(Declidisp $declidisp)
    {
        if ($this->service->hasPdx($declidisp->declinaison)) {
            $dpx = new DecliPrix();
            $dpx->chargerDeclidisp($declidisp->id);
            $dpx->delete();
        }
    }

    function ajdeclidisp(Declidisp $declidisp)
    {
        if ($this->service->hasPdx($declidisp->declinaison)) {
            $dpx = new DecliPrix();
            $dpx->id_declidisp = $declidisp->id;
            $dpx->prix = 0.00;
            $dpx->ref = "";
            $dpx->etat = 1;
            $dpx->add();
        }
    }

    /**
     * Créer les déclinaions de prix pour une déclinaison.
     *
     * @param mixed $arg
     */
    function ajdecliprix($arg = null)
    {
        $error = false;
        
        $tmp_array = array(
            "id_declinaison"
        );
        if ($arg) {}
        foreach ($tmp_array as $key) {
            if (empty($_POST[$key])) {
                $error = true;
                break;
            }
        }
        if (! $error) {
            $dpx = $this->service->getDpxByDeclination($_POST[$tmp_array[0]]);
            if (! $dpx) {
                $collection = $this->service->getDPXCollection();
            } else {
                $error = true;
            }
        }
        if ($error) {
            // Debug breakpoint
            $error;
        }
    }

    function moddeclinaison(Declinaison $declinaison)
    {
        $this->service->inserer();
    }

    private function _ajdecliprix()
    {
        $error = false;
        
        $tmp_array = array(
            "decliprix_id_declidisp",
            "decliprix_prix",
            "decliprix_ref",
            "ref"
        );
        
        foreach ($tmp_array as $key) {
            if (empty($_POST[$key])) {
                $error = true;
                break;
            }
        }
        
        if (! $error) {
            $decliprix = new DecliPrix();
            $decliprix->prix = $_POST['decliprix_prix'];
            $decliprix->ref = $_POST['decliprix_ref'];
            $decliprix->id_declidisp = $_POST['decliprix_id_declidisp'];
            
            $decliprix->add();
        }
    }
    
    function ajouterPanier($indiceAjouter) {
        $panier = $_SESSION['navig']->panier;
        $panier instanceof Panier;
        foreach ($panier->tabarticle as $article) {
            $article instanceof Article;
            $produit = new Produit();
            $produit->charger_id($article->produit->id);
            foreach ($article->perso as $perso) {
                $perso instanceof Perso;
                if($this->service->hasPdx($perso->declinaison)){
                    $decliprix = new Decliprix();
                    $decliprix->chargerDeclidisp($perso->valeur);
                    $article->produit->prix = $decliprix->prix;
                    $article->produit->ref = $produit->ref . "-" . $decliprix->ref;
                }
            }
        }
    } 
}
