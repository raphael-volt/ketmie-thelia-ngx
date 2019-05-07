<?php
require_once (dirname(realpath(__FILE__)) . '/../../../classes/filtres/FiltreCustomBase.class.php');

class Filtremenu extends FiltreCustomBase
{

    public function __construct()
    {
        parent::__construct("`\#FILTRE_menu\(([^\|]+)\|\|([^\)]+)\)`");
    }

    public static $currentFond = null;

    private $currentRub = 0;

    private $currentProd = 0;

    private function mergeGrid(DOMElement $data, DOMDocument $doc, $rubrique_id)
    {
        set_time_limit(0);
        $dir = "C:\wamp\www\ketmie-workspace\globals\images";
        $imgDir = THELIA_DIR . "/client/gfx/photos/grids";
        if (! is_dir($imgDir))
            mkdir($imgDir);
        $scan = scandir($dir);
        $files = array();
        $re = "/img_(\d+).*/";
        foreach ($scan as $name) {
            if (strpos(".", $name) === 0)
                continue;
            $f = "$dir/$name";
            if (is_dir($f))
                continue;
            $id = preg_match($re, $name, $match);
            $id = $match[1];
            $files[$id] = $name;
        }
        $xp = new DOMXPath($doc);
        $imgs = $xp->query("//img");
        $n = $imgs->length;
        for ($i = 0; $i < $n; $i ++) {
            $img = $imgs->item($i);
            $img instanceof DOMElement;
            $id = (int) $img->getAttribute("id");
            $name = $files[$id];
            $dot = strrpos($name, '.');
            $ext = substr($name, $dot + 1);
            
            $timg = new Image();
            $timg->rubrique = $rubrique_id;
            $imgId = $timg->add();
            $src = "$dir/$name";
            $name = "gimg_{$imgId}.{$ext}";
            $dst = "$imgDir/$name";
            copy($src, $dst);
            $timg->fichier = $name;
            $timg->maj();
            $img->setAttribute("id", $imgId);
            $img->setAttribute("src", $name);
        }
        $data->setAttribute("rubrique", $rubrique_id);
        $data->removeAttribute("article_id");
    }

    private static $grids = array();

    private function createGrid($rubrique_id, $output = true)
    {
        $rubrique_id = intval($rubrique_id);
        if (array_key_exists($rubrique_id, self::$grids)) {
            $output = self::$grids[$rubrique_id]["dom"];
            return $output->ownerDocument->saveXML($output);
        }
        $q = "SELECT * FROM grids WHERE rubrique=?";
        $pdo = PDOThelia::getInstance();
        $stmt = $pdo->prepare($q);
        $pdo->bindInt($stmt, 1, $rubrique_id);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_OBJ);
        if ($item == null)
            return "";
        $data = XMLUtils::getDom($item->grid_data);
        if ($data == null)
            return "";
        $doc = $data->ownerDocument;
        $doc instanceof DOMDocument;
        $id = Filtreimglist::IMG_LIST_CLASS . $data->getAttribute("id");
        self::$grids[$rubrique_id]["id"] = $id;
        self::$grids[$rubrique_id]["gap"] = intval($data->getAttribute("gap"));
        self::$grids[$rubrique_id]["width"] = intval($data->getAttribute("width"));
        $xp = new DOMXPath($doc);
        $output = XMLUtils::getDom("<ul id=\"" . $id . "\" class=\"" . Filtreimglist::IMG_LIST_CLASS . "\"/>");
        $outputDoc = $output->ownerDocument;
        $outputDoc instanceof DOMDocument;
        $gW = (int) $data->getAttribute("width");
        $rows = $xp->query("//row");
        $n = $rows->length;
        $res = array();
        for ($i = 0; $i < $n; $i ++) {
            $outRow = $outputDoc->createElement("ul");
            $output->appendChild($outRow);
            $row = $rows->item($i);
            $row instanceof DOMElement;
            $rW = $row->getAttribute("width");
            if ($gW - $rW > 10) {
                $outRow->setAttribute("class", "flex-left");
            }
            $imgs = $xp->query("img", $row);
            $m = $imgs->length;
            for ($j = 0; $j < $m; $j ++) {
                $img = $imgs->item($j);
                $img instanceof DOMElement;
                $prodLink = (int) $img->getAttribute("product_link_id");
                $oriWidth = (int) $img->getAttribute("original_width");
                $oriHeight = (int) $img->getAttribute("original_height");
                $h = (float) $img->getAttribute("height");
                $basename = (string) $img->getAttribute("src");
                $url = redim("grids", $basename, "", $h, "", "", "", 0);
                $img = $outputDoc->createElement("img");
                $img->setAttribute("src", $url);
                $a = $outputDoc->createElement("a");
                $href = Filtreimglist::getPreviewUrl($basename, $oriWidth, $oriHeight, "grids");
                $a->setAttribute("href", $href);
                $a->appendChild($img);
                if ($prodLink !== - 1) {
                    $input = $outputDoc->createElement("input");
                    $input->setAttribute("type", "hidden");
                    $input->setAttribute("value", $prodLink);
                    $a->appendChild($input);
                }
                $li = $outputDoc->createElement("li");
                $li->appendChild($a);
                if ($i < $n - 1)
                    $li->setAttribute("class", "gap-bottom");
                $outRow->appendChild($li);
            }
        }
        self::$grids[$rubrique_id]["dom"] = $output;
        if ($output)
            return $outputDoc->saveXML($output);
        return "";
    }

    public function calcule($match)
    {
        $match = parent::calcule($match);
        
        $action = $this->paramsUtil->action;
        $result = "";
        switch ($action) {
            case "gridCSS":
                {
                    if (count($match)) {
                        $rubrique_id = intval($match[0]);
                        if ($this->createGrid($rubrique_id, false) == "")
                            return "";
                        $id = "#" . self::$grids[$rubrique_id]["id"];
                        $width = intval(self::$grids[$rubrique_id]["width"]);
                        $margin = (948 - $width) / 2;
                        $gap = intval(self::$grids[$rubrique_id]["gap"]);
                        $padding = intval(self::$grids[$rubrique_id]["gap"]);
                        $bottom = $padding - 5;
                        $result = <<<EOL
{$id} {
width:{$width}px;							
margin:0 {$margin}px;
}

{$id} li.gap-bottom {
	margin-bottom: {$gap}px;
}

{$id} ul.flex-left li {
	margin-right: {$gap}px;
}
EOL;
                    }
                    // dieItem($result);
                    break;
                }
            case "grid":
                {
                    if (count($match)) {
                        $result = $this->createGrid($match[0]);
                    }
                    break;
                }
            case "cleanUrl":
                {
                    if (count($match)) {
                        $url = $match[0];
                        $result = str_replace('&amp;', '&', $url);
                    }
                    break;
                }
            case "arbo":
                {
                    if (count($match)) {
                        if (empty($match[0]))
                            $match[0] = 0;
                        $this->currentRub = intval($match[0]);
                    }
                    if (count($match) > 1) {
                        if (empty($match[1]))
                            $match[1] = - 1;
                        $this->currentProd = intval($match[1]);
                        if ($this->currentProd != - 1 && $this->currentRub == - 1) {
                            $prod = new Produit();
                            $prod->charger_id($this->currentProd);
                            $this->currentRub = $prod->rubrique;
                        }
                    }
                    if ($this->currentRub == - 1) {
                        if (self::$currentFond == "index" || self::$currentFond == "rubrique")
                            $this->currentRub = 0;
                    }
                    $result = $this->createMenu();
                    break;
                }
            case "ariane":
                {
                    $result = $this->createAriane();
                    break;
                }
            case "prodPrev":
                {
                    if (count($match))
                        $result = $this->getPrevProduit($match[0]);
                    break;
                }
            case "prodNext":
                {
                    if (count($match))
                        $result = $this->getNextProduit($match[0]);
                    break;
                }
            case "rubType":
                {
                    if (ProdDesc::$current)
                        $result = "produit";
                    else
                        $result = $this->getRubType();
                    break;
                }
            case "lien":
                {
                    if (count($match)) {
                        $pdo = PDOThelia::getInstance();
                        $q = "SELECT id FROM " . Rubrique::TABLE . " WHERE lien=?";
                        $stmt = $pdo->prepare($q);
                        $pdo->bindString($stmt, 1, $match[0]);
                        $result = intval($pdo->fetchColumn($stmt, 0, true));
                    }
                    
                    break;
                }
        }
        return $result;
    }

    private function getRubType()
    {
        $result = "none";
        if (RubDesc::$current) {
            if (RubDesc::$current->isProduitList())
                $result = "prodList";
            else {
                if (RubDesc::$current->content)
                    $result = "rubDesc";
                else
                    $result = RubDesc::$current->lien;
            }
        }
        return $result;
    }

    private static $nearestProduits = null;

    private function getNearestProduits($produit)
    {
        if (self::$nearestProduits)
            return self::$nearestProduits;
        $prod = new Produit();
        $prod->charger_id($produit);
        $rub = $prod->rubrique;
        
        $query = "SELECT classement, id FROM " . Produit::TABLE . " 
WHERE rubrique={$prod->rubrique} AND ligne=1 
ORDER BY classement";
        $pdo = PDOThelia::getInstance();
        $prods = $pdo->query($query);
        $prods = $pdo->fetchAll($prods, Produit::class);
        $prev = null;
        $next = null;
        $n = count($prods);
        if ($n < 2) {
            self::$nearestProduits = array(
                "",
                ""
            );
            return self::$nearestProduits;
        }
        for ($i = 0; $i < $n; $i ++) {
            $p = $prods[$i];
            $p instanceof Produit;
            if ($p->id == $produit) {
                if ($i == 0) {
                    $prev = $prods[$n - 1];
                    $next = $prods[$i + 1];
                    break;
                }
                $prev = $prods[$i - 1];
                if ($i == $n - 1) {
                    $next = $prods[0];
                    break;
                }
                $next = $prods[$i + 1];
                break;
            }
        }
        /*
         * self::$nearestProduits = array(
         * urlfond("produit", "id_produit={$prev->id}&id_rubrique={$rub}"),
         * urlfond("produit", "id_produit={$next->id}&id_rubrique={$rub}"));
         */
        self::$nearestProduits = array(
            $prev->id,
            $next->id
        );
        return self::$nearestProduits;
    }

    private function getNextProduit($produit)
    {
        $list = $this->getNearestProduits($produit);
        return $list[1];
    }

    private function getPrevProduit($produit)
    {
        $list = $this->getNearestProduits($produit);
        return $list[0];
    }

    private function createAriane($separator = "")
    {
        $r = RubDesc::$current;
        if (! $r)
            return "";
        $ariane = XMLUtils::getDom("<ul class=\"ariane\"/>");
        $doc = $ariane->ownerDocument;
        $doc instanceof DOMDocument;
        $l = array();
        while ($r->parent) {
            $l[] = array(
                $r->label,
                $r->isLinkable() ? $this->getRubDescUrl($r) : null
            );
            $r = $r->parent;
        }
        $l = array_reverse($l);
        if (ProdDesc::$current) {
            $l[] = array(
                ProdDesc::$current->label,
                $this->getProdDescUrl(ProdDesc::$current)
            );
        }
        $n = count($l);
        for ($i = 0; $i < $n; $i ++) {
            $r = $l[$i];
            $label = $r[0];
            $url = $r[1];
            $li = $doc->createElement("li");
            if ($i < $n - 1)
                $label .= $separator;
            $a = $doc->createElement($url == null ? "span" : "a", $label);
            if ($url)
                $a->setAttribute("href", $url);
            $li->appendChild($a);
            $ariane->appendChild($li);
        }
        return $doc->saveXML($ariane);
    }
    /**
     * 
     * @param boolean $checkRub
     * @return RubDesc
     */
    public function getArbo($checkRub=false){
        $pdo = PDOThelia::getInstance();
        $query = "SELECT d.titre as label, d.description as content, r.id, r.parent as pid, r.lien, r.classement AS child_index FROM rubrique AS r
LEFT JOIN rubriquedesc AS d ON d.rubrique=r.id
WHERE ligne=1
ORDER BY parent, child_index";
        
        $result = $pdo->query($query);
        $result = $result->fetchAll(PDO::FETCH_CLASS, RubDesc::class);
        
        $query = "SELECT d.titre as label, p.id, p.ref, p.rubrique FROM " . Produit::TABLE . " as p
LEFT JOIN " . Produitdesc::TABLE . " as d ON p.id=d.produit
WHERE ligne=1
ORDER BY rubrique, classement";
        $produits = $pdo->query($query);
        $produits = $produits->fetchAll(PDO::FETCH_CLASS, ProdDesc::class);
        $prodDict = array();
        if($checkRub) {    
            foreach ($produits as $prod) {
                $prod instanceof ProdDesc;
                $prodDict[$prod->rubrique][] = $prod;
                if ($prod->id == $this->currentProd)
                    ProdDesc::$current = $prod;
            }
        }
        $root = new RubDesc();
        $root->id = 0;
        $root->pid = 0;
        $rubs = array(
            0 => $root
        );
        if($checkRub && $this->currentRub == 0) {
            foreach ($result as $r) {
                $r instanceof RubDesc;
                if ($r->lien == RubDesc::HOME) {
                    $this->currentRub = intval($r->id);
                    break;
                }
            }
        }
        foreach ($result as $r) {
            $rubs[$r->id] = $r;
            $r instanceof RubDesc;
            if ($r->id == $this->currentRub) {
                RubDesc::$current = $r;
            }
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
        return $root;
    }
    private function createMenu()
    {
        $root = $this->getArbo(true);
        if (RubDesc::$current) {
            $r = RubDesc::$current;
            $r->on = true;
            while ($r->parent) {
                $r->on = true;
                $r = $r->parent;
            }
        }
        $ul = XMLUtils::getDom("<ul id=\"menu\"/>");
        $doc = $ul->ownerDocument;
        $rubs = $root->getRubChildren();
        foreach ($rubs as $r) {
            $r instanceof RubDesc;
            $this->rubRecurse($r, $ul, $doc);
        }
        
        return $doc->saveXML($ul);
    }

    private function rubRecurse(RubDesc $rub, DOMElement $node, DOMDocument $doc)
    {
        $li = $doc->createElement("li");
        
        if ($rub->isLinkable()) {
            if ($rub->lien == RubDesc::HOME)
                $url = urlsite();
            else
                $url = $this->getRubDescUrl($rub);
            
            $a = $doc->createElement("a", $rub->label);
            $a->setAttribute("href", $url);
        } else
            $a = $doc->createElement("span", $rub->label);
        
        $li->appendChild($a);
        $node->appendChild($li);
        $rubs = $rub->getRubChildren();
        $n = count($rubs);
        $cls = array(
            $rub->on ? "on" : "off"
        );
        if ($rub->pid == 0)
            $cls[] = "h-item";
        else
            $cls[] = "v-item";
        if ($n)
            $cls[] = "ctn-item";
        $cls = implode(" ", $cls);
        $li->setAttribute("class", $cls);
        if ($n) {
            $ul = $doc->createElement("ul");
            $li->appendChild($ul);
            foreach ($rubs as $r) {
                $this->rubRecurse($r, $ul, $doc);
            }
        }
    }

    private function getRubDescUrl(RubDesc $rub)
    {
        return urlfond("rubrique", "id_rubrique=$rub->id");
    }

    private function getProdDescUrl(ProdDesc $produit)
    {
        return urlfond("produit", "id_produit={$produit->id}&id_rubrique={$produit->rubrique}");
    }

    private function getProduitLinkById($produit_id, DOMDocument $doc)
    {
        $produit = new Produit();
        $produit->charger_id($produit_id);
        return $this->getProduitLink($produit, $doc);
    }

    private function getProduitLink(Produit $produit, DOMDocument $doc)
    {
        $url = $this->getProdDescUrl($produit);
        if (is_a($produit, ProdDesc::class)) {
            $pd = $produit;
            $pd instanceof ProdDesc;
            $label = $pd->label;
        } else
            $label = $produit->ref;
        $a = $doc->createElement("a", $label);
        $a->setAttribute("href", $url);
        return $a;
    }

    private function updateBOprods()
    {
        $pdo = PDOThelia::getInstance();
        $query = "SELECT d.titre as label, d.description as content, r.id, r.parent as pid, r.lien, r.classement AS child_index FROM rubrique AS r
LEFT JOIN rubriquedesc AS d ON d.rubrique=r.id
ORDER BY parent, child_index";
        
        $result = $pdo->query($query);
        $result = $result->fetchAll(PDO::FETCH_CLASS, RubDesc::class);
        
        $query = "SELECT d.titre as label, p.id, p.ref, p.rubrique FROM " . Produit::TABLE . " as p
LEFT JOIN " . Produitdesc::TABLE . " as d ON p.id=d.produit
ORDER BY rubrique, classement";
        $produits = $pdo->query($query);
        $produits = $produits->fetchAll(PDO::FETCH_CLASS, ProdDesc::class);
        $prodDict = array();
        foreach ($produits as $prod) {
            $prod instanceof ProdDesc;
            $prodDict[$prod->rubrique][] = $prod;
        }
        $root = new RubDesc();
        $root->id = 0;
        $root->pid = 0;
        $rubs = array(
            0 => $root
        );
        if ($this->currentRub == 0) {
            foreach ($result as $r) {
                $r instanceof RubDesc;
                if ($r->lien == RubDesc::HOME) {
                    $this->currentRub = intval($r->id);
                    break;
                }
            }
        }
        $rubBo = null;
        foreach ($result as $r) {
            $rubs[$r->id] = $r;
            $r instanceof RubDesc;
            
            if (! empty($r->content) && strlen($r->content))
                $r->content = true;
            else
                $r->content = false;
            if ($r->id == 3)
                $rubBo = $r;
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
        $boProd = array();
        $delRubs = array();
        foreach ($produits as $prod) {
            $prod instanceof ProdDesc;
            $p = $rubs[$prod->rubrique];
            while ($p) {
                $p instanceof RubDesc;
                if ($p == $rubBo) {
                    $boProd[] = $prod->id;
                    if (array_search($prod->rubrique, $delRubs) === false) {
                        $p = $rubs[$prod->rubrique];
                        while ($p != $rubBo) {
                            if (array_search($p->id, $delRubs) == false)
                                $delRubs[] = $p->id;
                            /*
                             * if( ! array_key_exists($p->id, $delRubs))
                             * $delRubs[$p->id] = $p->label;
                             */
                            $p = $p->parent;
                        }
                    }
                    break;
                }
                $p = $p->parent;
            }
        }
        /*
         * [12] => 2009
         * [13] => 2010
         * [14] => 2008
         * [15] => 2011
         * [16] => 2012
         * [17] => 2013
         * [18] => 2014
         * [23] => 2015
         */
        if (count($delRubs)) {
            $query = "DELETE FROM " . Rubrique::TABLE . " WHERE id=?";
            $stmt = $pdo->prepare($query);
            foreach ($delRubs as $id) {
                $pdo->bindInt($stmt, 1, $id);
                $stmt->execute();
            }
            $query = "UPDATE " . Produit::TABLE . " SET rubrique=3 WHERE id=?";
            $stmt = $pdo->prepare($query);
            foreach ($boProd as $id) {
                $pdo->bindInt($stmt, 1, $id);
                $stmt->execute();
            }
            return "PRODUITS UPDATE";
        }
        return "NO CHANGE";
        // die("<pre>" . print_r(array($delRubs, $boProd), true));
        // 3 1 Boucles d'oreille
    }

    public function statut($commande)
    {
        /*
         * // Les status des commandes
         * const NONPAYE = 1;
         * const PAYE = 2;
         * const TRAITEMENT = 3;
         * const EXPEDIE = 4;
         * const ANNULE = 5;
         */
        if ($commande->statut != "4") {
            ActionsModules::instance()->appel_module("preSubstitmail", $corps, $commande);
            $status = "";
            switch($commande->statut) {
                case "1": {
                    $status = "en attente de paiement";
                    break;
                }
                case "2": {
                    $status = "payé";
                    break;
                }
                case "3": {
                    $status = "traitement";
                    break;
                }
                case "5": {
                    $status = "annulé";
                    break;
                }
            }
            $emailcontact = Variable::lire("emailcontact");
            $emailfrom = Variable::lire("emailfrom");
            $nomsite = Variable::lire("nomsite");
            $url = urlsite();
            $logo_url = $url . "/" . Variable::lire("logo_site");
            
            /* Message client */
            $msg = new Message("statut_commande");
            $msgdesc = new Messagedesc($msg->id ,$commande->lang);
            
            $sujet = str_replace("__COMMANDE__", $commande->ref, $msgdesc->intitule);
            $corps = str_replace("__LOGO_SITE__", $logo_url, $msgdesc->description);
            $corps = str_replace("__STATUT__", $status, $corps);
            $corps = str_replace("__URLSITE__", $url, $corps);
            $corpstext = str_replace("__STATUT__", $status, $msgdesc->descriptiontext);
            $corpstext = str_replace("__URLSITE__", $url, $corpstext);
            
            
            $client = new Client();
            $client->charger_id($commande->client);
            
            Mail::envoyer("$client->prenom $client->nom", 
                $client->email, 
                Variable::lire('nomsite'), 
                Variable::lire('emailcontact'), $sujet, $corps, $corpstext);
        }
    }
}
