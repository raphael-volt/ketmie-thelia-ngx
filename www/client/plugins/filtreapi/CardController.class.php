<?php
require_once 'client/plugins/filtreapi/Request.class.php';
require_once 'client/plugins/filtreapi/HTTPReponseHelper.class.php';
require_once 'client/plugins/filtreapi/ProductHelper.class.php';

class CardController
{
    function CardController() {
        if (! isset($_SESSION['navig'])) {
            throw new Exception("missing session navig");
        }
        if (! isset($_SESSION['navig']->panier)) {
            $this->card = new Panier();
            $_SESSION['navig']->panier = $this->card;
        }
        else {
            $this->card = $_SESSION['navig']->panier;
        }
    }

    /**
     *
     * @var Panier
     */
    private $card;

    public function save()
    {
        $_SESSION['navig']->panier = $this->card;
        return $this->serializeCard();
    }

    public function add($ref, $quantite)
    {
        $this->card->ajouter($ref, $quantite);
    }

    public function remove($productId)
    {
        $this->card->supprimer($productId);
    }

    public function update($article, $quantite, $parent = -1)
    {
        $this->card->modifier($productId);
    }

    private function serializeCard()
    {
        $card = $this->card;
        $result = new stdClass();
        $items = array();
        if($card->tabarticle && count($card->tabarticle)) {
            foreach ($card->tabarticle as $article) {
                $article instanceof Article;
                $item = new stdClass();
                $items[] = $item;
                $item->productId = $article->produit->id;
                $item->decliId = $article->produitdesc->id;
                $item->quantity = $article->quantite;
                $item->parent = $article->parent;
                if ($article->perso 
                    && !is_array($article->perso)
                    && property_exists($article->perso, "declinaison")
                    && property_exists($article->perso, "valeur")) {
                    
                    $perso = new stdClass();
                    $perso->declination = $article->perso->declinaison;
                    $perso->value = $article->perso->valeur;
                    $item->perso = $perso;
                }
            }
            
        }
        
        $result->items = $items;
        $result->total = $card->total();
        return $result; // $this->_response->baseobj($this->card);
    }

    /**
     *
     * @param ParametersUtil $parms
     * @param unknown $input
     */
    public function setRequestParameters($parms, $input)
    {
        $method = $parms[0];
        $this->_response = new Response();
        $nPrams = count($parms);
        $p = $nPrams > 1 ? $parms[1] : null;
        $q = $nPrams > 2 ? $parms[2] : 1;
        switch ($method) {
            case "update":
                $this->update($p, $q);
                break;
            case "delete":
                $this->remove($p);
            default: // get
                ;
                break;
        }
        $this->setResponse(true, $this->save());
    }

    private function setResponse($sucess, $body)
    {
        $this->_response->success = $sucess;
        $this->_response->body = $body;
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
        $this->save();
        return $this->_response;
    }
}