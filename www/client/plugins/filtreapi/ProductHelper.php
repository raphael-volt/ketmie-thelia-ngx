<?php
class ProductJSON {
    public $id;
    public $label;
    public $price;
    public $ref;
    public $description;
    public $index;
    public $declinations;
    public $images;
}

class ProductHelper {
    /**
     * 
     * @param unknown $id
     * @param boolean $declinations
     * @return ProductJSON|NULL
     */
    public function getProduct($id, $declinations=false) {
        $pdo = PDOThelia::getInstance();
        $query = "SELECT d.titre as label, d.description, p.id, p.prix as price, p.ref, p.classement as `index` FROM " . Produit::TABLE . " as p
LEFT JOIN " . Produitdesc::TABLE . " as d ON p.id=d.produit
WHERE p.id=?";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        if($stmt->rowCount()) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, ProductJSON::class);
            $prod = $stmt->fetch();
            $prod instanceof ProductJSON;
            if($declinations) {
                $helper = new ProductHelper();
                $prod->declinations = $helper->getDeclinations($id);
                if($prod->declinations)
                    $prod->price = $prod->declinations[0]->price;
            }
            $stmt = $pdo->prepare("SELECT id FROM " . Image::TABLE . " WHERE produit=? ORDER BY classement");
            $pdo->bindInt($stmt, 1, $id, true);
            $prod->images = $pdo->fetchAllColumn($stmt);
            return $prod;
        }
        return null;
        
    }
    
	public function getDeclinations($id_produit) {
	    $carac = $this->getCarac($id_produit);
	    $result = null;
	    if($carac)
	    {
	        switch ($carac->caracteristique)
	        {
	            case BO_CARACTERISTIQUE:
	                {
	                    $result = $this->getBoDeclinations($carac->caracdisp);
	                    break;
	                }
	        }
	    }
	    return $result;
	}
	
	private function getBoDeclinations($caracdisp) {
	    $declinations = array();
        $carac = $this->loadCarac($caracdisp);
        $group = $carac->getGroup($caracdisp);
        foreach ($group->caracs as $caracId => $carac)
        {
            unset($carac->metal);
            $declinations[] = $carac;
        }
	    return $declinations;
	}
	/**
	 * @var BO_CaracHelper
	 */
	private static $caracHelper = null;
	
	/**
	 * @param unknown $declidisp
	 * @return BO_CaracHelper
	 */
	private function loadCarac($declidisp, $metal = "zinc")
	{
	    $declidisp = intval($declidisp);
	    if(self::$caracHelper)
	    {
	        if(self::$caracHelper->hasGroup($declidisp, $metal))
	            return self::$caracHelper;
	    }
	    $pdo = PDOThelia::getInstance();
	    $q = "SELECT cdd.titre, cdd.caracdisp,
bo.size, bo.price, bo.id, bo.metal
FROM caracdispdesc AS cdd
LEFT JOIN bo_carac AS bo ON bo.caracdisp=cdd.caracdisp
WHERE cdd.caracdisp=? AND bo.metal=?
ORDER BY cdd.titre, bo.size;";
	    $stmt = $pdo->prepare($q);
	    $pdo->bindInt($stmt, 1, $declidisp);
	    $pdo->bindString($stmt, 2, $metal);
	    $stmt->execute();
	    if(self::$caracHelper == null)
	    {
	        self::$caracHelper = new BO_CaracHelper($stmt->fetchAll(PDO::FETCH_OBJ));
	    }
	    else
	        self::$caracHelper->addRowGroup($stmt->fetchAll(PDO::FETCH_OBJ));
	        return self::$caracHelper;
	}
	
	/**
	 * @var Caracval
	 */
	private static $lastCaracVal;
	/**
	 * @param unknown $id_produit
	 * @return Caracval
	 */
	public function getCarac($id_produit)
	{
		if(self::$lastCaracVal && self::$lastCaracVal->produit == $id_produit)
			return self::$lastCaracVal;
		
		$pdo = PDOThelia::getInstance();
		$stmt = $pdo->query("SELECT * FROM " . Caracval::TABLE . " WHERE produit=$id_produit LIMIT 1;");
		$stmt = $stmt->fetchAll(PDO::FETCH_CLASS, Caracval::class);
		$result = null;
		if(count($stmt))
		{
			$result = $stmt[0];
		}
		self::$lastCaracVal = $result;
		return $result;
	}
	
}