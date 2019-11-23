<?php

class CommandHook
{

    /**
     *
     * @var Commande
     */
    public $commande;

    /**
     *
     * @var Filtreapi
     */
    private $service;

    private $catalog;

    function __construct()
    {
        $this->commande = new Commande();
        $this->service = new Filtreapi();
    }

    function getCatalog()
    {
        if (! $this->catalog)
            $this->catalog = $this->$service->catalog();
        return $this->catalog;
    }
    
    function getProducts() {
        $catalog = $this->getCatalog();
        $products = array();
        $this->parse($catalog, $products);
        return $products;
    }
    private function parse($src, &$products) {
        foreach ($src as $s) {
            ;
        }
    }

    /**
     *
     * @param Client $user
     */
    function createPanier(Client $client)
    {
        $client;
    }
}
?>