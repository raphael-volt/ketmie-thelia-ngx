<?php

class Filtrecarac extends FiltreCustomBase {
	function __construct() 
	{
		parent::__construct("`\#FILTRE_carac\(([^\|]+)\|\|([^\)]+)\)`");
		
	}
	public function calcule($match)
	{
		$match = parent::calcule($match);
		$action = $this->paramsUtil->action;
		$result = "";
		switch ($action)
		{
			case "pageret":
			{
				if(count($match))
					$_SESSION["navig"]->urlpageret = $match[0];
				break;
			}
			case "isBo":
			{
				$result = 0;
				if( ! count($match))
				{
					break;
				}
				$id_produit = intval($match[0]);
				$carac = $this->getCarac($id_produit);
				if($carac != null && $carac->caracteristique == BO_CARACTERISTIQUE)
				{
					$result = 1;
				}
				break;	
			}
			case "hasCarac":
			{
				if( ! count($match))
				{
					break;
				}
				$id_produit = intval($match[0]);
				$carac = $this->getCarac($id_produit);
				if($carac != null)
					$result = "1";
				break;	
			}
			case "produitPrice":
			{
				if(count($match))
				{
					$id_produit = intval($match[0]);
					$carac = $this->getCarac($id_produit);
					$price = false;
					if($carac != null)
					{
						if($carac->caracteristique != BO_CARACTERISTIQUE)
						{
							if( ! empty($carac->caracteristique))
							{
								$price = PDOThelia::getInstance()->query("SELECT price FROM carac_price WHERE caracteristique=$carac->caracteristique");
								$price = PDOThelia::getInstance()->fetchColumn($price);
								if($price == null)
									$price = false;
							}
						}
						else 
							break;
					}
					if($price === false) 
					{
						$price = PDOThelia::getInstance()->query("SELECT prix FROM " . Produit::TABLE . " WHERE id=$id_produit");
						$price = PDOThelia::getInstance()->fetchColumn($price);
						if($price == null)
							$price = "";
						
					}
					$result = $price;
					break;
				}
				break;
			}
			case "printPanier":
			{
				$p = self::getPanier();
				die("<pre>" . print_r($p->tabarticle, true));
				break;
			}
			case "print":
			{
				$nav = $_SESSION["navig"];
				$nav instanceof Navigation;
				dieItem(array(
						"ret"=>$nav->urlpageret,	
						"prec"=>$nav->urlprec));	
			}
			case "panierTable":
			{
				if(count($match) > 1)	
					$result = $this->getPanierTable($match[0], $match[1] == 1 ? true:false, count($match) > 2 ? $match[2]:null);
				break;
			}
			case "commandeTable":
			{
				if(count($match) > 0)	
					$result = $this->getCommandeTable($match[0]);
				break;
			}
			case "panierPriceCell":
			{
				if(count($match) < 2)
				{
					break;
				}
				
				$id = intval($match[0]);
				$articleIndex = intval($match[1]);
				$perso = $this->getPanierBoPerso($articleIndex);
				if($perso)
				{
					$metals = $this->getAvailableMetals($id);
					$node = XMLUtils::getDom('<select class="selPrice-button" name="declinaison5"/>');
					$doc = $node->ownerDocument;
					foreach ($metals as $metal)
					{
						$child = $doc->createElement("optgroup");
						$child->setAttribute("label", $metal);
						$node->appendChild($child);
						$carac = $this->loadCarac($id, $metal);
						try
						{
							$group = $carac->getGroup($id, $metal);
				
						}
						catch (Exception $e)
						{
							die($e->getTraceAsString());
						}
						foreach ($group->caracs as $caracId => $carac)
						{
							$carac instanceof BO_Carac;
							$this->addOptionNode($doc, $child, $carac, $carac->id == $perso->valeur);
						}
					}
					$result = $doc->saveXML($node);
				}
				
				break;
			}
			case "produit":
			{
				if( ! count($match))
				{
					break;
				}
				$id_produit = intval($match[0]);
				$carac = $this->getCarac($id_produit);
				
				if($carac == null)
				{
					$result = $this->getProdView($id_produit);
				}
				else 
				{
					switch ($carac->caracteristique) 
					{
						case BO_CARACTERISTIQUE:
						{
							$result = $this->getBOProdView($id_produit, $carac->caracdisp);
							break;
						}
						case COLLAGE_CARACTERISTIQUE:
						{
							$result = $this->getCollageProdView($id_produit, $carac->caracdisp);
							break;
						}
						default:
						{
							$result = $this->getProdView($id_produit);
							break;
						}
					}
				}
				break;
			}
		}
		return $result;
	}
	public function action()
	{	
		if(isset($_POST['action']) && $_POST['action'] == "modifierdecli")
		{
			$ref=null;
			$art = null;
			$dec = null;
			if(isset($_POST["article"]))
			{
				$art = $_POST['article'];
			}
			if(isset($_POST["ref"]))
			{
				$ref = $_POST['ref'];
			}
			if(isset($_POST["declinaison5"]))
			{
				$dec = intval($_POST['declinaison5']);
			}
				
			$price = $this->getPriceByBoCaracId($dec);
			if(is_nan($price))
				$this->dieItem($_POST);
				
			$article = $this->getPanierArticleAt($art);
			if(!$article)
				$this->dieItem($_POST);
				
			$perso = $this->getPanierBoPerso($art);
			if($perso)
			{
				$perso->valeur = $dec;
				$article->produit->prix = $price;
			}
			return;
			$navig = $_SESSION["navig"];
			$navig instanceof Navigation;
			redirige_action($navig->urlprec);
		}
	}
	private function getPriceByBoCaracId($id)
	{
		$id = intval($id);
		$pdo = PDOThelia::getInstance();
		$q = "SELECT price FROM bo_carac WHERE id=?";
		$stmt = $pdo->prepare($q);
		$pdo->bindInt($stmt, 1, $id);
		$carac = $pdo->fetchColumn($stmt, 0, true);
		if($carac)
		{
			return intval($carac);
		}
		return NAN;
	}
	
	public function dieItem($item)
	{
		if($item === null)
			$item = array("NULL");
		die("<pre>" . print_r($item, true));
	}
	/**
	 * @var Caracval
	 */
	private static $lastCaracVal;
	/**
	 * @param unknown $id_produit
	 * @return Caracval
	 */
	private function getCarac($id_produit)
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
	private function getAvailableMetals($caracdisp)
	{
		$caracdisp = intval($caracdisp);
		$q = "SELECT DISTINCT metal FROM bo_carac WHERE caracdisp=$caracdisp ;";
		$stmt = PDOThelia::getInstance()->query($q);
		$rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		$result = array();
		if(array_search("zinc", $rows) !== false)
			$result[] = "zinc";
	
		if(array_search("argent", $rows) !== false)
			$result[] = "argent";
	
		if(array_search("or", $rows) !== false)
			$result[] = "or";
		return $result;
	}
	private function getProdView($id_produit)
	{
		$desc = new Produitdesc($id_produit);
		$result = array();
		// $result[] = '<p>getProdView($id_produit)</p>';
		if(!empty($desc->chapo) && strlen($desc->chapo))
			$result[] = $desc->chapo;
		if(!empty($desc->description) && strlen($desc->description))
			$result[] = $desc->description;
		return implode(PHP_EOL, $result);
	}
	private function getCollageProdView($id_produit, $caracdisp)
	{
		$dict = $GLOBALS["dicotpl"];
		$result = array($dict["collageinfo"]);
		$pdo = PDOThelia::getInstance();
		$q = "SELECT description FROM produitdesc WHERE produit=?";
		$stmt = $pdo->prepare($q);
		$pdo->bindInt($stmt, 1, $id_produit, true);
		$description = $pdo->fetchColumn($stmt);
		if($description == null)
		{
			$q = "SELECT titre FROM caracdispdesc WHERE caracdisp=?";
			$stmt = $pdo->prepare($q);
			$pdo->bindInt($stmt, 1, $caracdisp, true);
			$caracval = $pdo->fetchColumn($stmt);
			
			$key = null;
			
			switch ($caracval) 
			{
				case "portrait":
				{
					$key = "colinfoportait";
					break;
				}
				
				case "paysage":
				{
					$key = "colinfopaysage";
					break;
				}
				
				default:
					;
				break;
			}
			if($key && array_key_exists($key, $dict))
				$result[] = $dict[$key];
		}
		else
		{
			$result[] = $description;
		}
		
		return implode(PHP_EOL, $result);
	}
	private function getBOProdView($id_produit, $caracdisp)
	{
		$metals = $this->getAvailableMetals($caracdisp);
		$xml = XMLUtils::getDom('<div class="produit-select"/>');
		$doc = $xml->ownerDocument;
		$doc instanceof DOMDocument;
		$dict = $GLOBALS["dicotpl"];
		$selected = "zinc";
		$xmls = array();
		if(count($metals) > 1)
		{
			if($numParams > 1)
				$selected = $match[1];
			$xmls[] = XMLUtils::appendXML($doc, $xml, $dict["declimetal"]);
			$node = $doc->createElement("p");
			$node = $doc->createElement("p");
			$node->setAttribute("class", "bold");
			$node->appendChild($doc->createTextNode($dict["boinfo-declimetal-select"]));
			$node->setAttribute("class", "bold");
			$child = $this->getMetalSelect($metals);
			$child = $doc->importNode($child, true);
			$node->appendChild($child);
			$xmls[] = $node;
			
			/*
			$node = $doc->createElement("p");
			$xml->appendChild($node);
			$node->setAttribute("class", "bold");
			$node->appendChild($doc->createTextNode($dict["boinfo-declimetal-select"]));
			$child = $this->getMetalSelect($metals);
			$child = $doc->importNode($child, true);
			$node->appendChild($child);
			$node = $doc->createElement("p", $dict["boinfo-declisize-select"]);
			$xml->appendChild($node);
			$xmls[] = $node;
			*/
		}
		else
		{
			$xmls[] = XMLUtils::appendXML($doc, $xml, $dict["boinfo1"]);
			$xmls[] = XMLUtils::appendXML($doc, $xml, $dict["boinfo2"]);
			$metals = array($selected);
		}
		
		foreach ($metals as $metal)
		{
			$node = $doc->createElement("ul");
			$class = array(
					"radio-list",
					$selected == $metal ? "on":"off"
			);
			$node->setAttribute("class", implode(" ", $class));
			$xml->appendChild($node);
			$xmls[] = $node;
				
			$carac = $this->loadCarac($caracdisp, $metal);
			$group = $carac->getGroup($caracdisp, $metal);
			foreach ($group->caracs as $caracId => $carac)
			{
				$carac instanceof BO_Carac;
				$this->addRadioNode($doc, $node, $carac);
			}
		}
		for ($i = 0; $i < count($xmls); $i++) 
		{
			$xmls[$i] = $doc->saveXML($xmls[$i]);
		}
		return implode(PHP_EOL, $xmls);
	}
	
	private function getRadioNodes($id, $metal, $selected)
	{
		$carac = $this->loadCarac($id, $metal);
		$group = $carac->getGroup($id, $metal);
		$xml = dom_import_simplexml(simplexml_load_string('<div/>'));
		$doc = $xml->ownerDocument;
		$doc->formatOutput = true;
		$doc->encoding = "utf-8";
		foreach ($group->caracs as $caracId => $carac)
		{
			$carac instanceof BO_Carac;
			$this->addRadioNode($doc, $xml, $carac, $carac->id == $selected);
		}
		return $doc->saveXML($xml);
	}
	private function addOptionNode(DOMDocument $doc, DOMElement $parent, BO_Carac $carac, $selected = false)
	{
		$node = $doc->createElement("option");
		$node instanceof DOMElement;
		$parent->appendChild($node);
		$node->setAttribute("value", $carac->id);
		$label = $carac->size . " cm | " . $carac->price . " €" ;
		$node->appendChild($doc->createTextNode($label));
		if($selected)
			$node->setAttribute("selected", "selected");
	}
	/**
	 * @param DOMDocument $doc
	 * @param DOMElement $parent
	 * @param BO_Carac $carac
	 * @param string $selected
	 * @return DOMElement
	 */
	private function addRadioNode(DOMDocument $doc, DOMElement $parent, BO_Carac $carac, $selected = false)
	{
		$div = $doc->createElement("li");
		$parent->appendChild($div);
		$div instanceof DOMElement;
		$div->setAttribute("class", "radioBox");
		$node = $doc->createElement("input");
		$div->appendChild($node);
		$node instanceof DOMElement;
		$cssid = "carac_$carac->id";
		$node->setAttribute("id", $cssid);
		$node->setAttribute("type", "radio");
		$node->setAttribute("name", "declinaison5");
		$node->setAttribute("value", $carac->id);
		if($selected)
			$node->setAttribute("checked", "checked");
		$label = $carac->size . " cm | " . $carac->price . " €" ;
		$node = $doc->createElement("label");
		$node->appendChild($doc->createTextNode($label));
		$node instanceof DOMElement;
		$div->appendChild($node);
		$node->setAttribute("for", $cssid);
		return $div;
	}
	
	/**
	 * @param unknown $metals
	 * @param string $selected
	 * @return DOMElement
	 */
	private function getMetalSelect($metals, $selected="zinc")
	{
		$xml = XMLUtils::getDom("<select class=\"metal-select\"/>");
		$xml->setAttribute("class", "metal-select");
		$doc = $xml->ownerDocument;
		foreach ($metals as $metal)
		{
			$this->addOptionMetalNode($doc, $xml, $metal, $selected == $metal);
		}
		return $xml;
	}
	private function addOptionMetalNode(DOMDocument $doc, DOMElement $parent, $metal, $selected = false)
	{
		$node = $doc->createElement("option");
		$node instanceof DOMElement;
		$parent->appendChild($node);
		$node->setAttribute("value", $metal);
		$label = $metal;
		$node->appendChild($doc->createTextNode($label));
		if($selected)
			$node->setAttribute("selected", "selected");
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
	 * @var Panier
	 */
	private static $panier;
	/**
	 * @return Panier
	 */
	private function getPanier()
	{
		if( ! self::$panier)
		{
			if(! isset($_SESSION["navig"]))
				return null;
			self::$panier = $_SESSION["navig"]->panier;
		}
		return self::$panier;
	}
	/**
	 * @param unknown $index
	 * @return multitype:Perso
	 */
	private function getPanierBoPerso($index)
	{
		$index = intval($index);
		$persos = $this->getPanierPersosAt($index);
		$perso = null;
		if(! $persos)
			return $perso;
		foreach($persos as $perso)
		{
			$perso instanceof Perso;
			if($perso->declinaison == 5)
				break;
			$perso = null;
		}
		return $perso;
	}
	/**
	 * @param unknown $index
	 * @return multitype:Perso
	 */
	private function getPanierPersosAt($index)
	{
		$index = intval($index);
		$article = $this->getPanierArticleAt($index);
		if( ! $article)
			return null;
		return $article->perso;
	}
	
	/**
	 * @param unknown $index
	 * @return Article
	 */
	private function getPanierArticleAt($index)
	{
		$index = intval($index);
		$panier = self::getPanier();
		if( ! $panier)
			return null;
	
		$data = $panier->tabarticle;
	
		if( ! array_key_exists($index, $data))
			return null;
	
		$data = $data[$index];
		$data instanceof Article;
		return $data;
	}
	
	private function setAttribute(DOMElement $element, $name, $value)
	{
		$element->setAttribute($name, $value);
	}
	private function setClass(DOMElement $element, $value)
	{
		$this->setAttribute($element, "class", $value);
	}
	/**
	 * @param string $label
	 * @param DOMElement $table
	 * @param DOMDocument $doc
	 * @param string $uClass
	 * @return DOMNode
	 */
	private function getTableColumn($label, DOMElement $table, DOMDocument $doc, $uClass=false)
	{
		$col = $table->appendChild($doc->createElement("div"));
		$this->setClass($col, "flex-col");
		$span = $col->appendChild($this->createElement("span", $doc, $label));
		$this->setClass($span, "head");
		$ul = $col->appendChild($doc->createElement("ul"));
		if($uClass !== false)
			$this->setClass($ul, $uClass);
		return $ul;
	}
	
	/**
	 * @var PDOStatement
	 */
	private $imgStmt;
	
	private function getProduitImg($id, $w="", $h="")
	{
		$pdo = PDOThelia::getInstance();
		if($this->imgStmt == null)
			$this->imgStmt = $pdo->prepare("SELECT fichier FROM " . Image::TABLE . " WHERE produit=? AND classement=1 LIMIT 1");
		$pdo->bindInt($this->imgStmt, 1, $id);
		$filename = PDOThelia::getInstance()->fetchColumn($this->imgStmt, 0, true);
		if($filename != null)
		{
			$filename = redim("produit", $filename, $w, $h);
		}
		return $filename;
	}
	/**
	 * @param Article $art
	 * @param DOMDocument $doc
	 * @return DOMElement li element
	 */
	private function getPanierTableDesc(Article $art, DOMDocument $doc, $thumbsize)
	{
		$li = $doc->createElement("li");
		$a = $li->appendChild($doc->createElement("a"));
		$href = urlfond("produit", "id_produit={$art->produit->id}&id_rubrique={$art->produit->rubrique}");
		$a->setAttribute('href', $href);
		$span = $a->appendChild($doc->createElement("span"));
		$this->setClass($span, "imgBox100");
		$href = $this->getProduitImg($art->produit->id, $thumbsize, $thumbsize);
		if($href)
		{
			$img = $doc->createElement("img");
			$img->setAttribute("src", $href);
			$span->appendChild($img);
		}
		$span = $a->appendChild($this->createElement("span", $doc, $art->produitdesc->titre));
		$this->setClass($span, "title");
		return $li;
	}
	/**
	 * @param unknown $str
	 * @param DOMElement $element
	 * @param DOMDocument $doc
	 * @return DOMText
	 */
	private function setTextNode($str, DOMElement $element, DOMDocument $doc)
	{
		return $element->appendChild($doc->createTextNode($str));
	}
	/**
	 * @param unknown $name
	 * @param DOMDocument $doc
	 * @param string $str
	 * @return DOMElement
	 */
	private function createElement($name, DOMDocument $doc, $str=false)
	{
		$element = $doc->createElement($name);
		if($str !== false)
			$this->setTextNode($str, $element, $doc);
		return $element;
	}
	
	/**
	 * @param unknown $perso_valeur
	 * @return BO_Carac
	 */
	private function getBoDecli($perso_valeur)
	{
		$id = intval($perso_valeur);
		$pdo = PDOThelia::getInstance();
		$q = "SELECT * FROM bo_carac WHERE id=?";
		$stmt = $pdo->prepare($q);
		$pdo->bindInt($stmt, 1, $id);
		return $pdo->fetch($stmt, BO_Carac, true);
	}
	
	private $carcdispStmt;
	/**
	 * @param DOMDocument $doc
	 * @param Perso $perso
	 * @return DOMElement
	 */
	private function getBoMetalSelector(DOMDocument $doc, Perso $perso)
	{
		$pdo = PDOThelia::getInstance();
		if($this->carcdispStmt == null)
		{
			$this->carcdispStmt = $pdo->prepare("SELECT caracdisp FROM bo_carac WHERE id=?");
		}
		$pdo->bindInt($this->carcdispStmt, 1, $perso->valeur);
		$carcdisp = $pdo->fetchColumn($this->carcdispStmt, 0, true);
		
		$metals = $this->getAvailableMetals($carcdisp);
		
		$node = $doc->createElement("select");
		$this->setClass($node, "selPrice-button");
		$node->setAttribute("name", "declinaison5");
		foreach ($metals as $metal)
		{
			$child = $doc->createElement("optgroup");
			$child->setAttribute("label", $metal);
			$node->appendChild($child);
			$carac = $this->loadCarac($carcdisp, $metal);
			
			$group = $carac->getGroup($carcdisp, $metal);
			// die("<pre>" . print_r($group, 1));
			foreach ($group->caracs as $caracId => $carac)
			{
				$carac instanceof BO_Carac;
				$this->addOptionNode($doc, $child, $carac, $carac->id == $perso->valeur);
			}
		}
		return $node;
	}
	
	private function appendInput($name, $value, DOMElement $parent, DOMDocument $doc) 
	{
		return $parent->appendChild($this->getInput($name, $value, $doc));
	}
	private function getInput($name, $value, DOMDocument $doc) 
	{
		$input = $doc->createElement("input");
		$input->setAttribute("type", "hidden");
		$input->setAttribute("name", $name);
		$input->setAttribute("value", $value);
		return $input;
	}
	
	private function getPanierTable($fond, $editable, $form=null) 
	{
		$urlsite = Variable::lire("urlsite");
		$thumbsize = Variable::lire("thumbsize");
		$table = XMLUtils::getDom('<div class="flex-table"/>');
		$doc = $table->ownerDocument;
		$doc instanceof DOMDocument;
		
		$dict = $GLOBALS["dicotpl"];
		$euro = $dict["euro"];
		$ulA = $this->getTableColumn($dict["nomarticle2"], $table, $doc, "desc-list");
		$ulP = $this->getTableColumn($dict["prixunitaire"], $table, $doc);
		$ulQ = $this->getTableColumn($dict["quantite"], $table, $doc);
		$ulT = $this->getTableColumn($dict["total"], $table, $doc);
		$this->setClass($ulT, "colTotal");
		$ulD = $this->getTableColumn($dict["supprimer"], $table, $doc);
		
		$panier = $this->getPanier();
		$params = null;
		
		$n = count($panier->tabarticle);
		
		for ($i = 0; $i < $n; $i++)
		{
			$art = $panier->tabarticle[$i];
			$art instanceof Article;
			$ulA->appendChild($this->getPanierTableDesc($art, $doc, $thumbsize));
			$perso = null;
			if($art->perso && count($art->perso))
			{
				$perso = $art->perso[0];
				$perso instanceof Perso;
				if($perso->declinaison != BO_DECLINAISON)
					$perso = null;
				else 
				{
					if($editable)
					{
						
						$node = $ulP->appendChild($doc->createElement("li"));
						$node = $node->appendChild($doc->createElement("form"));
						$node instanceof DOMElement;
						$node->setAttribute("action", $urlsite);
						$node->setAttribute("method", "post");
						
						$this->appendInput("action", "modifierdecli", $node, $doc);
						$this->appendInput("fond", $fond, $node, $doc);
						$this->appendInput("article", $i, $node, $doc);
						$this->appendInput("ref", $art->produit->ref, $node, $doc);
						
						$node->appendChild($this->getBoMetalSelector($doc, $perso));
					}
					else 
						$perso = null;
				}
			}
			if($perso == null)
				$ulP->appendChild($this->createElement("li", $doc, $this->price_format($art->produit->prix) . " $euro"));
			
			$perso = null;
			if($editable)
			{
				$node = $ulQ->appendChild($doc->createElement("li"));
				$node = $node->appendChild($doc->createElement("form"));
				$node->setAttribute("action", $urlsite);
				// $node->setAttribute("method", "post");
				$node->setAttribute("method", "get");
				
				$this->appendInput("fond", $fond, $node, $doc);
				$this->appendInput("action", "modifier", $node, $doc);
				$this->appendInput("article", $i, $node, $doc);
				
				$node = $node->appendChild($doc->createElement("select"));
				$this->setClass($node, "selNum-button");
				$node->setAttribute("name", "quantite");
				for ($j = 1; $j < 6; $j++) 
				{
					$opt = $node->appendChild($doc->createElement("option", "$j"));
					if($j == $art->quantite)
						$opt->setAttribute("selected", "selected");
					$opt->setAttribute("value", $j);
				}
			}
			else 
				$ulQ->appendChild($this->createElement("li", $doc, $art->quantite));
			
			$j = $art->quantite * $art->produit->prix;
			$ulT->appendChild($this->createElement("li", $doc, $this->price_format($j) . " $euro"));
			
			$node = $ulD->appendChild($doc->createElement("li"));
			$node = $node->appendChild($doc->createElement("a"));
			$href = urlfond($fond, "action=supprimer&article=$i");
			$node->setAttribute("href", $href);
			$node = $node->appendChild($doc->createElement("img"));
			$node->setAttribute("src", "./template/css/images/delete.png");
			$node->setAttribute("title", "supprimer l'article");
		}
		$j = $panier->total();
		$ulQ->appendChild($this->createElement("li", $doc, $dict["totalpanier"] . " :" ))->setAttribute("class", "rightCell");
		$ulT->appendChild($this->createElement("li", $doc, $this->price_format($j) ." $euro"))->setAttribute("class", "rightCell");
		if($fond == "commande")
		{
			$port = port();
			$ulQ->appendChild($this->createElement("li", $doc, $dict["fraislivraison"] . " :" ))->setAttribute("class", "rightCell");
			$ulT->appendChild($this->createElement("li", $doc, $this->price_format($port) ." $euro"))->setAttribute("class", "rightCell");
			$ulQ->appendChild($this->createElement("li", $doc, $dict["total"] . " :" ))->setAttribute("class", "rightCell total");
			$ulT->appendChild($this->createElement("li", $doc, $this->price_format($j+$port) ." $euro"))->setAttribute("class", "rightCell total");
			$ulQ->appendChild($this->createElement("li", $doc, $dict["choixmodepaiement"] . " :" ))->setAttribute("class", "rightCell");
			$node = $ulT->appendChild($doc->createElement("li"));
			$node->setAttribute("class", "rightCell");
			XMLUtils::appendXML($doc, $node, $form);
		}
		return $doc->saveXML($table);
	}
	private function getCommandeTable($commandeID) 
	{
		$urlsite = Variable::lire("urlsite");
		$thumbsize = Variable::lire("thumbsize");
		$table = XMLUtils::getDom('<div class="flex-table"/>');
		$doc = $table->ownerDocument;
		$doc instanceof DOMDocument;
		
		$dict = $GLOBALS["dicotpl"];
		$euro = $dict["euro"];
		$ulA = $this->getTableColumn($dict["nomarticle2"], $table, $doc, "desc-list");
		$ulP = $this->getTableColumn($dict["prixunitaire"], $table, $doc);
		$ulQ = $this->getTableColumn($dict["quantite"], $table, $doc);
		$ulT = $this->getTableColumn($dict["total"], $table, $doc);
		$this->setClass($ulT, "colTotal");
		
		$commande = new Commande($commandeID);
		
		$pdo = PDOThelia::getInstance();
		$q = "SELECT * FROM " . Venteprod::TABLE . " WHERE commande=?";
		$stmt = $pdo->prepare($q);
		$pdo->bindInt($stmt, 1, $commande->id);
		$vps = $pdo->fetchAll($stmt, Venteprod, true);
		
		$n = count($vps);
		
		for ($i = 0; $i < $n; $i++)
		{
			$vp = $vps[$i];
			$vp instanceof Venteprod;
			$art = new Article($vp->ref, $vp->quantite);
			$ulA->appendChild($this->getPanierTableDesc($art, $doc, $thumbsize));
			$ulP->appendChild($this->createElement("li", $doc, $this->price_format($vp->prixu) . " $euro"));
			$ulQ->appendChild($this->createElement("li", $doc, $vp->quantite));
				
			$j = $vp->quantite * $vp->prixu;
			$ulT->appendChild($this->createElement("li", $doc, $this->price_format($j) . " $euro"));
		}
		$j = $commande->total();
		$ulQ->appendChild($this->createElement("li", $doc, $dict["total"] . " :" ))->setAttribute("class", "rightCell");
		$ulT->appendChild($this->createElement("li", $doc, $this->price_format($j) ." $euro"))->setAttribute("class", "rightCell");
		
		$ulQ->appendChild($this->createElement("li", $doc, $dict["fraislivraison"] . " :" ))->setAttribute("class", "rightCell");
		$ulT->appendChild($this->createElement("li", $doc, $this->price_format($commande->port) ." $euro"))->setAttribute("class", "rightCell");
		
		$ulQ->appendChild($this->createElement("li", $doc, $dict["totalcommande"] . " :" ))->setAttribute("class", "rightCell total");
		$ulT->appendChild($this->createElement("li", $doc, $this->price_format($commande->total(true)) ." $euro"))->setAttribute("class", "rightCell total");
			
		return $doc->saveXML($table);
	}
	private function price_format($value)
	{
		return number_format(floatval($value), 2, ".", "");
	}
	
	public function processPaiement($type_paiement)
	{
		addLog(__CLASS__ . "::" . __FUNCTION__ . PHP_EOL . "\ttype_paiement:$type_paiement");
		/*
			PDOThelia::getInstance()->query("
			DELETE FROM commande WHERE 1;
			DELETE FROM venteadr WHERE 1;
			DELETE FROM ventedeclidisp WHERE 1;
			DELETE FROM venteprod WHERE 1;");
		*/
		if (! $_SESSION['navig']->client->id || $_SESSION['navig']->panier->nbart < 1)
			redirige(urlfond());
		$total = 0;
		$nbart = 0;
		$poids = 0;
		$unitetr = 0;
	
		ActionsModules::instance()->appel_module("avantcommande");
	
		$modules = new Modules();
		$modules->charger_id($type_paiement);
	
		if(! $modules->actif) return 0;
	
		try {
	
			$modpaiement = ActionsModules::instance()->instancier($modules->nom);
	
			$commande = new Commande();
			$commande->transport = $_SESSION['navig']->commande->transport;
			$commande->client = $_SESSION['navig']->client->id;
			$commande->remise = 0;
	
			$devise = ActionsDevises::instance()->get_devise_courante();
			$commande->devise = $devise->id;
			$commande->taux = $devise->taux;
	
			$client = new Client();
			$client->charger_id($_SESSION['navig']->client->id);
	
			$adr = new Venteadr();
			$adr->raison = $client->raison;
			$adr->entreprise = $client->entreprise;
			$adr->nom = $client->nom;
			$adr->prenom = $client->prenom;
			$adr->adresse1 = $client->adresse1;
			$adr->adresse2 = $client->adresse2;
			$adr->adresse3 = $client->adresse3;
			$adr->cpostal = $client->cpostal;
			$adr->ville = $client->ville;
			$adr->tel = $client->telfixe . "  " . $client->telport;
			$adr->pays = $client->pays;
			$adrcli = $adr->add();
			$commande->adrfact = $adrcli;
	
			$adr = new Venteadr();
			$livraison = new Adresse();
	
			if($livraison->charger($_SESSION['navig']->adresse)){
	
				$adr->raison = $livraison->raison;
				$adr->entreprise = $livraison->entreprise;
				$adr->nom = $livraison->nom;
				$adr->prenom = $livraison->prenom;
				$adr->adresse1 = $livraison->adresse1;
				$adr->adresse2 = $livraison->adresse2;
				$adr->adresse3 = $livraison->adresse3;
				$adr->cpostal = $livraison->cpostal;
				$adr->ville = $livraison->ville;
				$adr->tel = $livraison->tel;
				$adr->pays = $livraison->pays;
	
			}
			else {
				$adr->raison = $client->raison;
				$adr->entreprise = $client->entreprise;
				$adr->nom = $client->nom;
				$adr->prenom = $client->prenom;
				$adr->adresse1 = $client->adresse1;
				$adr->adresse2 = $client->adresse2;
				$adr->adresse3 = $client->adresse3;
				$adr->cpostal = $client->cpostal;
				$adr->ville = $client->ville;
				$adr->tel = $client->telfixe . "  " . $client->telport;
				$adr->pays = $client->pays;
			}
	
			$adrlivr = $adr->add();
			$commande->adrlivr = $adrlivr;
	
			$commande->facture = 0;
	
			$commande->statut=Commande::NONPAYE;
			$commande->paiement = $type_paiement;
	
			$commande->lang = ActionsLang::instance()->get_id_langue_courante();
	
			$commande->id = $commande->add();
	
			$pays = new Pays();
			$pays->charger($adr->pays);
	
			$correspondanceParent = array(null);
	
			foreach($_SESSION['navig']->panier->tabarticle as $pos => &$article) 
			{
				$venteprod = new Venteprod();
	
				$dectexte = "<br />";
	
				$produit = new Produit();
	
				$stock = new Stock();
	
				foreach($article->perso as $perso) {
	
					$perso instanceof Perso;
					if(is_numeric($perso->valeur) && $modpaiement->defalqcmd) {
	
						// diminution des stocks de déclinaison si on est sur un module de paiement qui défalque de suite
						$stock->charger($perso->valeur, $article->produit->id);
						$stock->valeur-=$article->quantite;
						$stock->maj();
					}
					if($perso->declinaison == BO_DECLINAISON)
					{
						$carac = $this->getBoDecli($perso->valeur);
						$dectexte .= "$carac->metal | $carac->size cm ";
						//self::addLog("BO_DECLINAISON");
						continue;
					}
					//self::addLog("STANDARD_DECLINAISON");
					$declinaison = new Declinaison();
					$declinaisondesc = new Declinaisondesc();
	
	
					$declinaison->charger($perso->declinaison);
					$declinaisondesc->charger($declinaison->id);
	
					// recup valeur declidisp ou string
					if($declinaison->isDeclidisp($perso->declinaison)){
						$declidisp = new Declidisp();
						$declidispdesc = new Declidispdesc();
						$declidisp->charger($perso->valeur);
						$declidispdesc->charger_declidisp($declidisp->id);
						$dectexte .= "- " . $declinaisondesc->titre . " : " . $declidispdesc->titre . "\n";
					}
					else
						$dectexte .= "- " . $declinaisondesc->titre . " : " . $perso->valeur . "\n";
	
				}
	
				// diminution des stocks classiques si on est sur un module de paiement qui défalque de suite
	
				$produit = new Produit($article->produit->ref);
	
				if($modpaiement->defalqcmd){
					$produit->stock-=$article->quantite;
					$produit->maj();
				}
	
				// Gestion TVA
				$prix = $article->produit->prix;
				$prix2 = $article->produit->prix2;
				$tva = $article->produit->tva;
	
				if($pays->tva != "" && (! $pays->tva || ($pays->tva && $_SESSION['navig']->client->intracom != "" && !$pays->boutique))) {
					$prix = round($prix/(1+($tva/100)), 2);
					$prix2 = round($prix2/(1+($tva/100)), 2);
					$tva = 0;
				}
	
				$venteprod->quantite =  $article->quantite;
	
				if( ! $article->produit->promo)
					$venteprod->prixu =  $prix;
				else
					$venteprod->prixu =  $prix2;
	
				$venteprod->ref = $article->produit->ref;
				$venteprod->titre = $article->produitdesc->titre . " " . $dectexte;
				$venteprod->chapo = $article->produitdesc->chapo;
				$venteprod->description = $article->produitdesc->description;
				$venteprod->tva =  $tva;
	
				$venteprod->commande = $commande->id;
				$venteprod->id = $venteprod->add();
				//self::addLog(print_r($venteprod, true));
				$correspondanceParent[]=$venteprod->id;
	
				// ajout dans ventedeclisp des declidisp associées au venteprod
				foreach($article->perso as $perso){
					$declinaison = new Declinaison();
					$declinaison->charger($perso->declinaison);
	
					// si declidisp (pas un champs libre)
					if($perso->declinaison == BO_DECLINAISON || $declinaison->isDeclidisp($perso->declinaison)){
						$vdec = new Ventedeclidisp();
						$vdec->venteprod = $venteprod->id;
						$vdec->declidisp = $perso->valeur;
						$vdec->add();
					}
				}
	
				ActionsModules::instance()->appel_module("apresVenteprod", $venteprod, $pos);
				$total += $venteprod->prixu * $venteprod->quantite;
				$nbart++;
				$poids+= $article->produit->poids;
			}
			//self::addLog("TOTAL_COMMANDE:$total");
			foreach($correspondanceParent as $id_panier => $id_venteprod) {
	
				if($_SESSION['navig']->panier->tabarticle[$id_panier]->parent>=0) {
	
					$venteprod->charger($id_venteprod);
					$venteprod->parent = $correspondanceParent[$_SESSION['navig']->panier->tabarticle[$id_panier]->parent];
					$venteprod->maj();
				}
			}
	
			$pays = new Pays($_SESSION['navig']->client->pays);
	
			if ($_SESSION['navig']->client->pourcentage>0) $commande->remise = $total * $_SESSION['navig']->client->pourcentage / 100;
	
			$total -= $commande->remise;
	
			if($_SESSION['navig']->promo->id != ""){
	
				$commande->remise += calc_remise($total);
	
				$_SESSION['navig']->promo->utilise = 1;
	
				if(!empty($commande->remise))
					$commande->remise = round($commande->remise, 2);
	
				$commande->maj();
				$temppromo = new Promo();
				$temppromo->charger_id($_SESSION['navig']->promo->id);
	
				$temppromo->utilise++;
	
				$temppromo->maj();
	
				$promoutil = new Promoutil();
				$promoutil->commande = $commande->id;
				$promoutil->promo = $temppromo->id;
				$promoutil->code = $temppromo->code;
				$promoutil->type = $temppromo->type;
				$promoutil->valeur = $temppromo->valeur;
				$promoutil->add();
			}
	
			if($commande->remise > $total)
				$commande->remise = $total;
	
			$commande->port = port();
			if(intval($commande->port) <= 0) $commande->port = 0;
	
			$_SESSION['navig']->promo = new Promo();
			$_SESSION['navig']->commande = $commande;
	
			$commande->transaction = genid($commande->id, 6);
	
			// $this->printAndDie($commande);
				
			$commande->maj();
	
			$total = $_SESSION['navig']->panier->total(1,$_SESSION['navig']->commande->remise) + $_SESSION['navig']->commande->port;
				
			if($total<$_SESSION['navig']->commande->port)
				$total = $_SESSION['navig']->commande->port;
	
			$_SESSION['navig']->commande->total = $total;
	
			ActionsModules::instance()->appel_module("aprescommande", $commande);
	
			addLog("COMMANDE PROCESS COMPLETE");
			// printInLog($commande);
			TheliaDeliveryMailer::sendMails($commande);
			addLog("/!\\  REDIRECTION FAILURE /!\\");
			
	
		} catch (Exception $e) {
			// FIXME: Echec de commande -> cas à traiter ?
		}
	}
}