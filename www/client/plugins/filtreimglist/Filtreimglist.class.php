<?php

class ProdImgRow
{
	private $_items = array();
	public function setItems(array $value)
	{
		$this->_items = $value;
	}
	
	public function getNumItems()
	{
		return count($this->_items);
	}
	/**
	 * @return multitype:ProdImg
	 */
	public function getItems()
	{
		return $this->_items;
	}
	
	public $maxX = 0;
	/**
	 * @param mixed $index
	 * @return ProdImg
	 */
	public function getItemAt($index)
	{
		if(array_key_exists($index, $this->_items))
		{
			return $this->_items[$index];
		}
		return null;
	}
	
	public function addItem(ProdImg $prodImg)
	{
		$this->_items[] = $prodImg;
	}
}
class ProdImg
{
    const NO_IMG = "no-image.png";
	public static function validate($items, $reqWidth=NAN, $reqHeight=NAN, $dirname="produit")
	{
		$dir = THELIA_DIR . "/client/gfx/photos/$dirname";
		$n = count($items);
		for ($i = 0; $i < $n; $i++) 
		{
			$p = $items[$i];
			$p instanceof ProdImg;
			if( ! $p->getValidated())
				$p->validateSource($dir);
			if(is_nan($reqWidth) && is_nan($reqHeight))
				continue;
			if( ! is_nan($reqWidth))
				$p->setRequestedWidth($reqWidth);
			else 
				if(! is_nan($reqHeight))
					$p->setRequestedHeight($reqHeight);
		}
	}
	/**
	 * @param array $items
	 * @param mixed $maxWidth
	 * @param mixed $gap
	 * @return multitype:ProdImgRow
	 */
	public static function getRows(array $prodImgList, $thumbHeight, $maxWidth, $gap)
	{
		$row = new ProdImgRow();
		$rows = array($row);
		
		$totalW = 0;
		$rowW = 0;
		$prev_H = $thumbHeight;
		$nProd = count($prodImgList);
		for ($i=0; $i<$nProd; $i++)
		{
			$p = $prodImgList[$i];
			$p instanceof ProdImg;
			if($row->maxX + $p->requestedWidth >= $maxWidth)
			{
				$row->maxX = $maxWidth;
				$row->addItem($p);
				$rowW = 0;
				$n = $row->getNumItems();
				$tW = $maxWidth - ($n-1) * $gap;
				for($j=0; $j<$n; $j++)
					$rowW += $row->getItemAt($j)->requestedWidth;
		
				$s = $tW / $rowW;
				$rH = floor($s * $thumbHeight);
		
				ProdImg::validate($row->getItems(), NAN, $rH);
				$prev_H = $rH;
				$row = new ProdImgRow();
				$rows[] = $row;
			}
			else
			{
				$row->addItem($p);
				$row->maxX += $p->requestedWidth + $gap;
			}
		}
		if( ! count($row->getItems()))
			array_pop($rows);
		return $rows;
	}
	
	public $id;
	public $label;
	public $fichier;
	
	public $width = NAN;
	public $height = NAN;
	public $requestedWidth = NAN;
	public $requestedHeight = NAN;
	private $scale = NAN;

	private $mime=false;
	private $imageType=false;
	
	private $src;
	
	private function setDefault()
	{
		$this->scale = NAN;
		$this->width = NAN;
		$this->height = NAN;
		$this->requestedWidth = NAN;
		$this->requestedHeight = NAN;
		$this->mime = false;
		$this->imageType = false;
	}
	
	public function getImageType()
	{
		return $this->imageType;
	}
	
	public function getMime()
	{
		return $this->mime;
	}
	
	public function getValidated()
	{
		return $this->mime !== false;
	}
	
	public function validateSource($dirname)
	{
	    $filename = "$dirname/$this->fichier";
	    if(! $this->fichier || ! file_exists($filename)) {
	        $this->fichier = self::NO_IMG;
	        $dirname = THELIA_DIR . "/client/gfx/photos/global";
	    }
		if($this->src == $filename)
			return $this->getValidated();
		$this->src = $filename;	        
		
		$size = getimagesize("$dirname/$this->fichier");
		if($size === false)
		{
			$this->setDefault();
			return false;
		}
		$this->width = $size[0];
		$this->height = $size[1];
		$this->imageType = $size[2];
		$this->mime = $size["mime"];
		return true;
	}

	private function throwInvalideError()
	{
		if( ! $this->getValidated())
			throw new Exception("Missing image file");
		else 
			return true;
		return false;
	}
	
	private function throwSizeError($value, $prop)
	{
		if(is_nan($value) || $value <= 0)
		{
			throw new Exception("Invalide size $prop");
		}
		else
			return true;
		return false;
	}
	
	public function setRequestedWidth($value)
	{
		$value = $this->validateRequestedSize($value, $this->requestedWidth, "width");
		if($value === false)
			return;
		$this->scale = $value / $this->width;
		
		$this->requestedHeight = floor($this->height * $this->scale);
		$this->requestedWidth = $value;	
	}
	
	public function setRequestedHeight($value)
	{
		$value = $this->validateRequestedSize($value, $this->requestedHeight, "height");
		if($value === false)
			return;
		
		$this->scale = $value / $this->height;
		
		$this->requestedWidth = floor($this->width * $this->scale);
		$this->requestedHeight = $value;
	}
	
	private function validateRequestedSize($newValue, $currentValue, $prop)
	{
		$this->throwInvalideError();
		$this->throwSizeError($newValue, $prop);
		$newValue = floor($newValue);
		if($currentValue == $newValue)
			return false;
		return $newValue;
	}
}

class Filtreimglist extends FiltreCustomBase

{
	const IMG_MAX_W = 800;
	const IMG_MAX_H = 600;
	const IMG_LIST_CLASS = "img_list";
	
	function __construct()
	{
		parent::__construct("`\#FILTRE_imglist\(([^\|]+)\|\|([^\)]+)\)`");
	}
	
	public function calcule($match)
	{
		$match = parent::calcule($match);
		$action = $this->paramsUtil->action;
		$result = "";
		switch ($action)
		{
			case "rubrique":
			{
				$n = count($match);
				if(! $n)
				{
					break;
				}
				$id_rubrique = $match[0];
				if($n > 1)
					$width = intval($match[1]);
				else
					$width = 960;
				if($n > 2)
					$height = intval($match[2]);
				if($n > 3)
					$gap = intval($match[3]);
				else
					$gap = 5;
				try {
				$result = $this->getRubriqueDom($id_rubrique, $width, $height, $gap);
					
				} catch (Exception $e) {
					dieItem($e);
				}
				break;
			}
			case "produit":
			{
				$n = count($match);
				if(! $n)
				{
					break;
				}
				$id_produit = $match[0];
				
				if($n > 1)
					$width = intval($match[1]);
				else
					$width = 300;
				
				if($n > 2)
					$height = intval($match[2]);
				else 
					$height = 100;
				
				if($n > 3)
					$gap = intval($match[3]);
				else
					$gap = 5;

				try {
					$result = $this->getProduitDom($id_produit, $width, $height, $gap);					
				} catch (Exception $e) {
					dieItem($e);
				}
				break;
			}
		}
		return $result;
	}

	private function getProduitDom($id_produit, $width, $thumbHeight, $gap)
	{
		$query = "SELECT fichier FROM " . Image::TABLE . " WHERE produit=? ORDER BY classement;";
		$pdo = PDOThelia::getInstance();
		$stmt = $pdo->prepare($query);
		$pdo->bindInt($stmt, 1, $id_produit);
		$prodImgList = $pdo->fetchAll($stmt, ProdImg::class, true);
		$n = count($prodImgList);
		if(!$n)
		{
			$pimg = new ProdImg();
			$pimg->id = $id_produit;
			$prodImgList = array($pimg);
		}
		ProdImg::validate($prodImgList);
		
		
		$dl = XMLUtils::getDom("<dl class=\"img-prod-desc\"/>");
		$doc = $dl->ownerDocument;
		$doc instanceof DOMDocument;
		
		$p = array_shift($prodImgList);
		
		$p instanceof ProdImg;
		$node = $dl->appendChild($doc->createElement("dt"));
		$a = $node->appendChild($doc->createElement("a"));
		$p->setRequestedWidth($width);
		if($p->requestedHeight > $width)
			$p->setRequestedHeight($width);
		
		$this->_setImgLink($a, $p);
		if($p->fichier != ProdImg::NO_IMG)
    		$src = redim("produit", $p->fichier, $width);
		else 
		    $src = self::redimNoImg($width);
		$this->_appendImageItem($a, $doc, $p, $src);
		
		$n = count($prodImgList);
		$node = $dl->appendChild($doc->createElement("dd"));
		if($n)
		{
			$rows = $this->_getImgGridRows($prodImgList, $width, $thumbHeight, $gap);
			$ul = $this->_getProduitImgGridDom($prodImgList, $rows, $width, $thumbHeight, $gap);
			$ul = $doc->importNode($ul, true);
			$node->appendChild($ul);
		}
		else 
			$node->setAttribute("class", "imglist-empty");
		
		return $doc->saveXML($dl);
	}
	
	private function validateImages($prodImgList)
	{
		$n = count($prodImgList);
		for ($i = 0; $i < $n; $i++) 
		{
			$p = $prodImgList[$i];
			$p instanceof ProdImg;
			$p->validateSize();
		}
	}
	private function getRubriqueDom($id_rubrique, $width, $thumbHeight, $gap=5)
	{
		$query = "SELECT p.id, d.titre as label, i.fichier FROM produit AS p
LEFT JOIN produitdesc AS d ON d.produit=p.id
LEFT JOIN image AS i ON i.produit=p.id AND i.classement=1
WHERE p.rubrique=? AND p.ligne = 1
ORDER BY p.classement;";
		$pdo = PDOThelia::getInstance();
		$stmt = $pdo->prepare($query);
		$pdo->bindInt($stmt, 1, $id_rubrique);
		$prodImgList = $pdo->fetchAll($stmt, ProdImg::class, true);
		$n = count($prodImgList);
		if(!$n)
			return "";
		
		$rows = $this->_getImgGridRows($prodImgList, $width, $thumbHeight, $gap);
		$ul = $this->_getRubriqueImgGridDom($id_rubrique, $rows, $width, $thumbHeight, $gap);
		
		return $ul->ownerDocument->saveXML($ul);
	}
	
	private function _getImgGridRows($prodImgList, $width, $thumbHeight, $gap)
	{
		ProdImg::validate($prodImgList, NAN, $thumbHeight);
		return ProdImg::getRows($prodImgList, $thumbHeight, $width, $gap);
	}
	
	private function _getRubriqueImgGridDom($id_rubrique, $rows, $width, $thumbHeight, $gap)
	{
		$ul = XMLUtils::getDom("<ul class=\"img_list\"/>");
		$doc = $ul->ownerDocument;
		$doc instanceof DOMDocument;
	
		$nRow = count($rows);
		for ($i = 0; $i < $nRow; $i++)
		{
			$row = $rows[$i];
			$row instanceof ProdImgRow;
			$n = $row->getNumItems();
			$r = $doc->createElement("li");
			if($nRow > 1)
			{
				if($i < $nRow - 1)
					$r->setAttribute("class", "gap-bottom");
			}
			$ul->appendChild($r);
			$r = $r->appendChild($doc->createElement("ul"));
			for ($j = 0; $j < $n; $j++) 
			{
				$this->_appendRubriqueImgItem($r, $doc, $row->getItemAt($j), $id_rubrique);
			}
		}
		if($n && $row->maxX != $width)
		{
			$r->setAttribute("class", "flex-left");
		}
		return $ul;
	}
	/*
	 * Redimensionnement et traitement d'une image
	 */
	static function redimNoImg($dest_width="", $dest_height="") {
	    $opacite="";
	    $nb="";
	    $miroir="";
	    $exact=0;
	    $couleurfond="ffffff";
	    
	    $fichier = ProdImg::NO_IMG;
	    $dirname = THELIA_DIR . "/client/gfx/photos/global";
	    $nomorig = "$dirname/$fichier";
	    $nomcache  = "client/cache/" . $type . "/" . $dest_width . "_" . $dest_height . "_" . $opacite . "_" . $nb . "_" . $miroir . "_" . $exact . "_" . $couleurfond . "_" . $nsimple[1] . "." . $nsimple[2];
	    $pathcache = THELIA_DIR . "/$nomcache";
	    if (file_exists($pathcache)
	        ||
	        traiter_et_cacher_image($nomorig, $pathcache, $dest_width, $dest_height, $opacite, $nb, $miroir, $exact, $couleurfond)) {
	            
	            return $nomcache;
	        }
	    
	    return "";
	}
	private function _appendRubriqueImgItem(DOMElement $parent, DOMDocument $doc, ProdImg $p, $id_rubrique)
	{
		$li = $parent->appendChild($doc->createElement("li"));
		$a = $li->appendChild($doc->createElement("a"));
		$href = urlfond("produit", "id_produit={$p->id}&id_rubrique={$id_rubrique}");
		$a->setAttribute("href", $href);
			
		if($p->fichier == ProdImg::NO_IMG){
		  $src = self::redimNoImg("", $p->requestedHeight);   
		}
		else $src = redim("produit", $p->fichier, "", $p->requestedHeight);
		
		$this->_appendImageItem($a, $doc, $p, $src);
		$a->appendChild($doc->createElement("span", $p->id));
	}
	
	private function _getProduitImgGridDom($prodImgList, $rows, $width, $thumbHeight, $gap)
	{
		$ul = XMLUtils::getDom("<ul class=\"" . self::IMG_LIST_CLASS . "\"/>");
		$doc = $ul->ownerDocument;
		$doc instanceof DOMDocument;
	
		$nRow = count($rows);
		for ($i = 0; $i < $nRow; $i++)
		{
			$row = $rows[$i];
			$row instanceof ProdImgRow;
			$n = $row->getNumItems();
			$r = $doc->createElement("li");
			if($nRow > 1)
			{
				if($i < $nRow - 1)
					$r->setAttribute("class", "gap-bottom");
			}
			$ul->appendChild($r);
			$r = $r->appendChild($doc->createElement("ul"));
				
			for($j=0; $j<$n; $j++)
			{
				$this->_appendArticleImgItem($r, $doc, $row->getItemAt($j));
			}
			$r->setAttribute("class", "flex-left");
		}
		if($nRow && $row->maxX != $width)
		{
			$r->setAttribute("class", "flex-center");
		}
		return $ul;
	}

	public static function getPreviewUrl($fichier, $oriWidth, $oriHeight, $type="produit")
	{
		$s = self::IMG_MAX_W / $oriWidth;
		if($s * $oriHeight > self::IMG_MAX_H)
		{
			$s = self::IMG_MAX_H / $oriHeight;
		}
		$rw = floor($s * $oriWidth);
		if($fichier == ProdImg::NO_IMG) {
		    return self::redimNoImg($rw);
		}
		return "image.php?name={$fichier}&width={$rw}&type={$type}";
	}
	private function _setImgLink(DOMElement $a, ProdImg $p)
	{
		$href = self::getPreviewUrl($p->fichier, $p->width, $p->height);
		$a->setAttribute("href", $href);
	}
	
	private function _appendArticleImgItem(DOMElement $parent, DOMDocument $doc, ProdImg $p)
	{
		$li = $parent->appendChild($doc->createElement("li"));
		$a = $li->appendChild($doc->createElement("a"));

		$this->_setImgLink($a, $p);
			
		$src = redim("produit", $p->fichier, "", $p->requestedHeight);
		
		$this->_appendImageItem($a, $doc, $p, $src);
	}
	
	private function _appendImageItem(DOMElement $a, DOMDocument $doc, ProdImg $p, $src)
	{
		$img = $a->appendChild($doc->createElement("img"));
		$img->setAttribute("width", $p->requestedWidth);
		$img->setAttribute("height", $p->requestedHeight);
		$img->setAttribute("src", $src);
	}
	
	/* produit||#PRODUIT_ID||438||100||5 */
	
	public function dieItem($item)
	{
		self::printItem($item);
	}
	public static function printItem($item)
	{
		die("<pre>" . print_r($item, true));
	}
}