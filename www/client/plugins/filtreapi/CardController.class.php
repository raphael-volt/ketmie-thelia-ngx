<?php
require_once 'client/plugins/filtreapi/Request.class.php';
require_once 'client/plugins/filtreapi/HTTPReponseHelper.class.php';
require_once 'client/plugins/filtreapi/ProductHelper.class.php';
define("BO_ID", 5);

class CardItem
{

    public $index = null;

    public $productId = null;

    public $decliId = null;

    public $quantity = null;

    public $parent = null;

    public $perso = null;

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

    function CardController()
    {
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
        /*
         * $persos = array();
         * foreach ($item->perso as $value) {
         * $p = new Perso();
         * $p->declinaison = $value->declinaison;
         * $p->valeur = $value->valeur;
         * $persos[] = $p;
         * }
         * return $persos;
         */
        return array();
    }

    private function setProductPrice($i, $value)
    {
        $this->card->tabarticle[$i]->produit->prix = $value;
    }

    private function checkPrice($i, $declidisp)
    {
        $p = new ProductHelper();
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
        $ref = $this->getProductRef($item->productId);
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
     * @param unknown $method
     * @param unknown $input
     * @return Response
     */
    public function setRequestParameters($method, $input)
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
                $send = $this->serializeCard();
                break;
        }
        
        return $this->setResponse(true, $send);
    }

    function logClear()
    {
        file_put_contents(__DIR__ . '/card.add.log', "");
    }

    function log($data){
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
     * @param unknown $index
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
     * @param unknown $index
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
     * @param unknown $index
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
     * @param unknown $sucess
     * @param unknown $body
     * @return Response
     */
    private function setResponse($sucess, $body)
    {
        $this->updateSession();
        $data = new stdClass();
        $data->total = $this->card->total();
        $data->data = $body;
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
        $item->productId = $article->produit->id;
        $item->decliId = $article->produitdesc->id;
        $item->quantity = $article->quantite;
        $item->parent = $article->parent;
        if ($index !== null)
            $item->index = intval($index);
        if ($article->perso && ! is_array($article->perso) && property_exists($article->perso, "declinaison") && property_exists($article->perso, "valeur")) {
            
            $item->perso = $this->serializePerso($article->perso);
        }
        return $item;
    }

    private function serializeArticles()
    {
        $card = $this->_card;
        $items = array();
        $l = $this->card->tabarticle;
        for ($i = 0; $i < $this->card->nbart; $i ++) {
            $items[] = $this->serializeArticle($l[$i], $i);
        }
        /*
         * foreach ($card->tabarticle as $key => $value) {
         * $items->{$key} = $this->serializeArticle($value);
         * }
         */
        return $items;
    }

    private function serializeCard()
    {
        $card = $this->card;
        $result = new stdClass();
        $result->items = $this->serializeArticles();
        $result->total = $card->total();
        return $result;
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