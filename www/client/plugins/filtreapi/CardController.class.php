<?php

require_once dirname(realpath(__FILE__)) . '/Request.class.php';
require_once dirname(realpath(__FILE__)) . '/HTTPReponseHelper.class.php';
require_once dirname(realpath(__FILE__)) . '/ProductHelper.class.php';
define("BO_ID", 5);

class CardItem
{

    public $index = null;

    public $productId = null;

    public $decliId = null;

    public $quantity = null;

    public $parent = null;

    public $perso = null;

    public $declinations = null;

    function CardItem($json)
    {
        $this->load($json);
    }

    function load($json)
    {
        if (! $json) {
            
            return;
        }
        foreach ($json as $key => $value) {
            $this->{$key} = $value;
        }
        if (is_nan($this->quantity) || $this->quantity < 0)
            $this->quantity = 1;
    }
}

class CardController
{

    public $productHelper;

    function CardController()
    {
        $this->productHelper = new ProductHelper();
        if (! isset($_SESSION['navig'])) {
            throw new Exception("missing session navig");
        }
        if (! isset($_SESSION['navig']->panier)) {
            $this->card = new Panier();
            $_SESSION['navig']->panier = $this->card;
        } else {
            $this->card = $_SESSION['navig']->panier;
        }
    }

    /**
     *
     * @var Panier
     */
    private $card;

    private function updateSession()
    {
        $_SESSION['navig']->panier = $this->card;
    }

    /**
     *
     * @param CardItem $item
     * @return Perso[]
     */
    private function getPersos($item)
    {
        $persos = array();
        foreach ($item->perso as $value) {
            $p = new Perso();
            $p->declinaison = $value->declinaison;
            $p->valeur = $value->valeur;
            $persos[] = $p;
        }
        return $persos;
    }

    private function setProductPrice($i, $value)
    {
        $this->card->tabarticle[$i]->produit->prix = $value;
    }

    private function checkPrice($i, $declidisp)
    {
        $p = $this->productHelper;
        $d = $p->getPriceByBoCaracId($declidisp);
        if (! is_nan($d)) {
            $this->setProductPrice($i, $d);
        }
        return $d;
    }

    /**
     *
     * @param CardItem $item
     * @return Article
     */
    public function add($item)
    {
        $ref = $this->getProductRef($item->product->id);
        if ($item->perso)
            $item->perso = $this->getPersos($item);
        $n = count($this->card->tabarticle);
        $this->card->ajouter($ref, $item->quantity, $item->perso);
        $this->checkPrice($n, $item->decliId);
        return $this->card->tabarticle[$n];
    }

    /**
     *
     * @param CardItem $item
     */
    public function update($item)
    {
        if ($item->perso)
            $item->perso = $this->getPersos($item);
        if ($item->quantity == null)
            $item->quantity = 1;
        
        $article = $this->card->tabarticle[$item->index];
        $article->quantite = $item->quantity;
        $this->card->modifier($item->index, $item->quantity);
        $this->checkPrice($item->index, $item->decliId);
        return $this->card->tabarticle[$item->index];
    }

    /**
     *
     * @param CardItem $item
     */
    public function remove($item)
    {
        $index = strval($item->index);
        $this->card->supprimer($index);
    }

    public function clear()
    {
        $n = count($this->card->tabarticle);
        for ($i = 0; $i < $n; $i ++) {
            $this->card->supprimer(0);
        }
    }

    /**
     *
     * @param mixed $method
     * @param mixed $input
     * @return Response
     */
    public function setRequestParameters($method, $input, $map = 0)
    {
        $this->_response = new Response();
        $item = new CardItem($input);
        $send = null;
        
        switch ($method) {
            case "add":
                $this->add($item);
                break;
            case "update":
                $this->update($item);
                break;
            case "remove":
                $this->remove($item);
                break;
            case "clear":
                $this->clear();
                break;
            
            default: // get
                $send = $this->serializeArticles(); // $this->serializeCard();
                break;
        }
        
        return $this->setResponse(true, $send, $map);
    }

    function logClear()
    {
        file_put_contents(__DIR__ . '/card.add.log', "");
    }

    /**
     *
     * @param mixed $sucess
     * @param mixed $body
     * @return Response
     */
    private function setResponse($sucess, $body, $map)
    {
        $this->updateSession();
        $data = new stdClass();
        $data->total = $this->card->total();
        $data->data = $body;
        if ($map == 1) {
            $data->map = $this->productHelper->getDeclinationsMap();
        }
        
        $this->_response->success = $sucess;
        $this->_response->body = $data;
        return $this->_response;
    }

    private function getProductRef($id)
    {
        $pdo = PDOThelia::getInstance();
        $stmt = $pdo->query("SELECT ref FROM " . Produit::TABLE . " WHERE id=$id LIMIT 1");
        if ($stmt->rowCount())
            return $stmt->fetchColumn();
        return null;
    }

    /**
     *
     * @param Perso $perso
     */
    private function serializePerso($perso)
    {
        if (! $perso)
            return null;
        $result = new stdClass();
        $result->declination = $perso->perso->declinaison;
        $result->value = $perso->perso->valeur;
        return $result;
    }

    /**
     *
     * @param Article $article
     * @param Perso $perso
     */
    private function serializeArticle($article, $index = null)
    {
        $item = new stdClass();
        $item->product = new stdClass();
        $item->product->price = $article->produit->prix;
        $item->product->ref = $article->produit->ref;
        $item->product->label = $article->produitdesc->titre;
        $item->product->id = $article->produit->id;
        
        $item->quantity = intval($article->quantite);
        $item->parent = $article->parent;
        $item->decliId = 0;
        if ($index !== null)
            $item->index = intval($index);
        if ($article->perso && ! is_array($article->perso) && property_exists($article->perso, "declinaison") && property_exists($article->perso, "valeur")) {
            
            $item->perso = $this->serializePerso($article->perso);
        }
        $pid = $article->produitdesc->id;
        $carac = $this->productHelper->getCarac($pid);
        if ($carac) {
            $group = $this->productHelper->getBoDecliGroup($carac->caracdisp);
            $decli = new stdClass();
            $decli->label = $group->titre;
            $decs = $this->productHelper->getBoDeclinations($carac->caracdisp);
            $decli->items = $decs;
            
            $item->product->declinations = array(
                $carac->caracdisp
            );
            
            $price = $article->produit->prix;
            $dec = $this->getDecliByPrice($price, $decs);
            if ($dec == null) {
                $dec = $decs[0];
                $pchanged = true;
            }
            if ($price != $dec->price) {
                $pchanged = true;
            }
            if ($item->decliId != $dec->id) {
                $item->decliId = $dec->id;
            }
            
            if ($pchanged) {
                $article->produit->prix = $dec->price;
            }
            
            $item->perso = $persos;
            if ($pchanged) {
                $this->log(array(
                    "[CARD.SERIALIZE] invalid perso"
                ));
                $article = $this->card->tabarticle[$item->index];
                $article->perso = $persos;
                $this->card->modifier($index, $article->quantite);
            }
        }
        
        return $item;
    }

    /**
     *
     * @param mixed $value
     * @param BO_Carac[] $declis
     * @return BO_Carac
     */
    private function getDecliByPrice($value, $declis)
    {
        $value = floatval($value);
        foreach ($declis as $d) {
            if (floatval($d->price) == $value) {
                return $d;
            }
        }
        return null;
    }

    private function serializeArticles()
    {
        $card = $this->card;
        $items = array();
        $l = $this->card->tabarticle;
        for ($i = 0; $i < $this->card->nbart; $i ++) {
            $items[] = $this->serializeArticle($l[$i], $i);
        }
        
        return $items;
    }

    private function serializeCard()
    {
        $card = $this->card;
        $result = new stdClass();
        $result->items = $this->serializeArticles();
        $result->total = $card->total();
        $result->map = $this->productHelper->getDeclinationsMap();
        
        return $result;
    }

    function log($data)
    {
        file_put_contents(__DIR__ . '/card.add.log', print_r($data, true), FILE_APPEND);
    }

    function logAction($name, $item = null, $articles = true, $data = null)
    {
        $l = array(
            "0" => $name,
            "total" => $this->card->total()
        );
        if ($item) {
            $l["item"] = $item;
        }
        if ($articles) {
            $l["articles"] = $this->card->tabarticle;
        }
        if ($data) {
            $l["1"] = $data;
        }
        file_put_contents(__DIR__ . '/card.add.log', print_r($l, true), FILE_APPEND);
    }

    /**
     *
     * @param mixed $index
     * @return multitype:Perso
     */
    private function getPanierBoPerso($index)
    {
        $index = intval($index);
        $persos = $this->getPanierPersosAt($index);
        $perso = null;
        if (! $persos)
            return $perso;
        foreach ($persos as $perso) {
            $perso instanceof Perso;
            if ($perso->declinaison == BO_ID)
                break;
            $perso = null;
        }
        return $perso;
    }

    /**
     *
     * @param mixed $index
     * @return multitype:Perso
     */
    function getPanierPersosAt($index)
    {
        $index = intval($index);
        $article = $this->getPanierArticleAt($index);
        if (! $article)
            return null;
        return $article->perso;
    }

    /**
     *
     * @param mixed $index
     * @return Article
     */
    function getPanierArticleAt($index)
    {
        $index = intval($index);
        $panier = $this->card;
        if (! $panier)
            return null;
        
        $data = $panier->tabarticle;
        
        if (! array_key_exists($index, $data))
            return null;
        
        $data = $data[$index];
        $data instanceof Article;
        return $data;
    }

    /**
     *
     * @var Response
     */
    private $_response;

    /**
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->_response;
    }
}