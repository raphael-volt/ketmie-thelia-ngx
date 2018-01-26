<?php
require_once (dirname(realpath(__FILE__)) . '/../../../classes/filtres/FiltreCustomBase.class.php');

class Filtreapi extends FiltreCustomBase
{

    public function __construct()
    {
        parent::__construct("`\#FILTRE_api\(([^\|]+)\|\|([^\)]+)\)`");
    }

    private function descriptions($type, $id)
    {
        $result = new stdClass();
        $tn = null;
        switch ($type) {
            case "cms-content":
                $tn = "contenudesc";
                break;
            case "category":
                $tn = "rubriquedesc";
                break;
            
            case "product":
                $tn = "produitdesc";
                break;
            
            default:
                ;
                break;
        }
        if (! $tn) {
            $result->error = "Unable to find decription for type:'{$type}'";
            return $result;
        }
        $pdo = PDOThelia::getInstance();
        $stmt = $pdo->prepare("SELECT description FROM {$tn} WHERE id=?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        if($stmt->rowCount()) {
            $result->description = $stmt->fetchColumn(0);
        }
        else {
            $result->error = "Object of type:'{$type}' not found";            
        }
        return $result;
    }

    private function arbo()
    {
        $arbo = new stdClass();
        $arbo->shopCategories = $this->catalog();
        $q = "SELECT c.id as id, c.classement as ci, cd.titre as label FROM contenu as c 
LEFT JOIN contenudesc as cd ON cd.id=c.id
ORDER BY c.classement";
        $pdo = PDOThelia::getInstance();
        $cms = $pdo->query($q);
        $arbo->cmsContents = $cms->fetchAll(PDO::FETCH_OBJ);
        return $arbo;
    }

    private function catalog()
    {
        $pdo = PDOThelia::getInstance();
        $query = "SELECT d.titre as label, d.description as content, r.id, r.parent as pid, r.lien, 
r.classement AS child_index FROM rubrique AS r
LEFT JOIN rubriquedesc AS d ON d.rubrique=r.id
ORDER BY parent, child_index";
        
        $result = $pdo->query($query);
        $result = $result->fetchAll(PDO::FETCH_CLASS, RubDesc::class);
        $query = "SELECT d.titre as label, p.id, p.ref, p.rubrique FROM " . Produit::TABLE . " as p 
LEFT JOIN " . Produitdesc::TABLE . " as d ON p.id=d.produit 
WHERE ligne=1 ORDER BY rubrique, classement";
        $produits = $pdo->query($query);
        $produits = $produits->fetchAll(PDO::FETCH_CLASS, ProdDesc::class);
        
        $prodDict = array();
        foreach ($produits as $prod) {
            $prod instanceof ProdDesc;
            $prodDict[$prod->rubrique][] = $prod;
            if ($prod->id == $this->currentProd)
                ProdDesc::$current = $prod;
        }
        $root = new RubDesc();
        $root->id = 0;
        $root->pid = 0;
        $rubs = array(
            0 => $root
        );
        
        foreach ($result as $r) {
            $rubs[$r->id] = $r;
            $r instanceof RubDesc;
            if (! empty($r->content) && strlen($r->content))
                $r->content = true;
            else
                $r->content = false;
        }
        foreach ($rubs as $id => $r) {
            $r instanceof RubDesc;
            if ($r == $root)
                continue;
            $p = $rubs[$r->pid];
            $p instanceof RubDesc;
            $r->parent = $p;
            $i = $r->child_index - 1;
            $p->children[$i] = $r;
        }
        foreach ($rubs as $id => $r) {
            $r instanceof RubDesc;
            if ($r == $root)
                continue;
            if (array_key_exists($r->id, $prodDict)) {
                $r->children = array_merge($r->children, $prodDict[$r->id]);
            }
        }
        return $root->toJson()->children;
    }

    public function calcule($match)
    {
        $match = parent::calcule($match);
        $action = $this->paramsUtil->action;
        $result = new stdClass();
        try {
            switch ($action) {
                case "catalog":
                    {
                        $result = $this->catalog();
                        break;
                    }
                case "arbo":
                    {
                        $result = $this->arbo();
                        break;
                    }
                case "descriptions":
                    {
                        $result = $this->descriptions($this->paramsUtil->parameters[0], $this->paramsUtil->parameters[1]);
                        break;
                    }
            }
        } catch (Exception $e) {
            $result->error = $e->getTraceAsString();
        }
        return json_encode($result);
    }
}
