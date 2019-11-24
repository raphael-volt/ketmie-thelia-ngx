<?php

class CatalogService
{

    private function serialize(Baseobj $obj)
    {
        $vars = $obj->bddvars;
        $result = new stdClass();
        foreach ($vars as $k) {
            $result->{$k} = $obj->{$k};
        }
        
        return $result;
    }

    private $catalog;

    function getCatalog()
    {
        if ($this->catalog)
            return $this->catalog;
        return $this->createCatalog();
    }

    function getDeclinations()
    {
        $pdo = PDOHook::getPDO();
        $stmt = $pdo->query('SELECT car.id as caracteristiqueId, carD.titre, 
cd.id as caracdispId, 
cdD.titre as caracTitre,
cv.produit,
bc.size, bc.price, bc.id as boId
FROM `caracteristique` as car 
LEFT JOIN caracteristiquedesc as carD ON carD.caracteristique = car.id
LEFT JOIN caracdisp as cd ON cd.caracteristique = car.id
LEFT JOIN caracdispdesc as cdD ON cdD.caracdisp = cd.id
LEFT JOIN caracval as cv ON cv.caracdisp = cd.id
LEFT JOIN bo_carac as bc ON bc.caracdisp = cd.id
WHERE cv.produit IS NOT NULL
ORDER BY caracteristiqueId, caracTitre, produit, bc.size');
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        
        while ($row = $stmt->fetch()) {}
    }

    private function createCatalog()
    {
        $pdo = PDOHook::getPDO();
        $stmt = $pdo->query("SELECT id FROM " . Produit::TABLE . " WHERE ligne=1");
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        $products = array();
        
        foreach ($ids as $id) {
            $prod = new Produit();
            $prod->charger_id($id);
            $prod = $this->serialize($prod);
            $prod->declinations = $this->getProductDeclination($id);
            $products[$prod->rubrique][] = $prod;
        }
        $rubriques = array();
        foreach ($products as $rid => $products) {
            $rub = new Rubrique();
            $rub->charger_id($rid);
            $rub = $this->serialize($rub);
            $desc = new Rubriquedesc();
            $desc->charger_id($rid);
            
            $rub->titre = $desc->titre;
            $rub->products = $products;
            $rubriques[$rid] = $rub;
        }
        $this->catalog = $rubriques;
        return $rubriques; 
    }

    private $_decliStmt;

    function getProductDeclination($id)
    {
        if (! $this->_decliStmt) {
            $q = 'SELECT cdd.titre, cdd.caracdisp,
bo.size, bo.price, bo.id as bo_carac
FROM caracdispdesc AS cdd
LEFT JOIN bo_carac AS bo ON bo.caracdisp=cdd.caracdisp
LEFT JOIN caracval as cv ON cv.produit = ?
WHERE cv.caracdisp = bo.caracdisp
ORDER BY cdd.titre, bo.size;';
            $stmt = PDOHook::getPDO()->prepare($q);
            $this->_decliStmt = $stmt;
        } else
            $stmt = $this->_decliStmt;
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $result;
    }

    function getBODeclinations()
    {
        $q = 'SELECT cdd.titre, cdd.caracdisp,
bo.size, bo.price, bo.id as bo_carac
FROM caracdispdesc AS cdd
LEFT JOIN bo_carac AS bo ON bo.caracdisp=cdd.caracdisp
WHERE bo.id is not null
ORDER BY cdd.titre, bo.size';
        $stmt = PDOHook::getPDO()->query($q);
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $map = array();
        
        foreach ($res as $row) {
            if(! array_key_exists($row->caracdisp, $map)) {
                $dec = new stdClass();
                $dec->caracdisp = $row->caracdisp;
                $dec->titre = $row->titre;
                $dec->items = array();
                $map[$row->caracdisp] = $dec;
            }
            $item = new stdClass();
            $item->bo_carac = $row->bo_carac;
            $item->size = $row->size;
            $item->price = $row->price;
            $dec->items[] = $item;
        }
        return $map;
    }
}

class CommandHook
{

    /**
     *
     * @var Commande
     */
    public $commande;

    /**
     *
     * @var CatalogService
     */
    private $service;

    private $catalog;

    function __construct()
    {
        $this->commande = new Commande();
        $this->service = new CatalogService();
    }

    function getCatalog()
    {
        return $this->service->getCatalog();
    }

    function getProducts()
    {
        $catalog = $this->getCatalog();
        $products = array();
        $this->parse($catalog, $products);
        return $products;
    }

    private function parse($src, &$products)
    {
        foreach ($src as $s) {
            ;
        }
    }
    
    function findDeclinable($nb=10) {
        $catalog = $this->getCatalog();
        $result = array();
        $count = 0;
        foreach ($catalog as $rub) {
            if(count($result) == $nb) {
                break;
            }
            foreach ($rub->products as $p) {
                $decs = $p->declinations;
                if(count($decs)) {
                    $result[] = $p;
                }
                if(count($result) == $nb) {
                    break;
                }
            }
        }
        return $result;
    }
    
    function findSimple($nb=10) {
        $catalog = $this->getCatalog();
        $result = array();
        $count = 0;
        foreach ($catalog as $rub) {
            if(count($result) == $nb) {
                break;
            }
            foreach ($rub->products as $p) {
                $decs = $p->declinations;
                if(!count($decs)) {
                    $result[] = $p;
                }
                if(count($result) == $nb) {
                    break;
                }
            }
        }
        return $result;
    }
}
?>