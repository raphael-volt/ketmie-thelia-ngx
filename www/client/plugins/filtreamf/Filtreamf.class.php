<?php

class Filtreamf extends FiltreCustomBase 
{
	function __construct()
	{
		parent::__construct("`\#FILTRE_amf\(([^\|]+)\|\|([^\)]+)\)`");

	}
	
	public function calcule($match)
	{
		$match = parent::calcule($match);
		$action = $this->paramsUtil->action;
		$result = "";
		switch ($action) 
		{
			case "model":
			{
				$urlSite = Variable::lire("urlsite");
				$w = 100;
				$h = 0;
				$numParams = $this->paramsUtil->getNumParameters();
				if($numParams)
				{
					$rw = intval($match[1]);
					if($rw)
						$w = $rw;
				}
				if($numParams > 1)
				{
					$rh = intval($match[2]);
					if($rh)
						$h = $rh;
				}
				if( ! $h)
					$h = $w;
						
				$result = $this->getModel($urlSite, $w, $h);
				break;
			}
			case "grids":
			{
				$pdo = PDOThelia::getInstance();
				$q = "SELECT grid_data FROM grids";
				$stmt = $pdo->query($q);
				$grids = $pdo->fetchAllColumn($stmt);
				$dom = XMLUtils::getDom("<grids/>");
				$doc = $dom->ownerDocument;
				$doc instanceof DOMDocument;
				foreach ($grids as $grid) 
				{
					$grid = XMLUtils::getDom($grid);
					$grid = $doc->importNode($grid, true);
					$dom->appendChild($grid);
				}
				$result = $doc->saveXML($dom);
				break;
			}
			default:
				;
			break;
		}
		return $result;
	}
	
	private function updateGrids()
	{
		$pdo = PDOThelia::getInstance();
		$q = "SELECT * FROM grids";
		$stmt = $pdo->query($q);
		$grids = $pdo->fetchAll($stmt, stdClass);
		$q = "UPDATE grids SET grid_data=? WHERE id=?";
		$stmt = $pdo->prepare($q);
		foreach ($grids as $grid)
		{
			$dom = XMLUtils::getDom($grid->grid_data);
			if( ! $dom->hasAttribute("version"))
				continue;
			$doc = $dom->ownerDocument;
			$doc instanceof DOMDocument;
			$dom->removeAttribute("version");
			$dom->removeAttribute("changed");
			$dom->setAttribute("id", $grid->id);
			$pdo->bindString($stmt, 1, $doc->saveXML($dom));
			$pdo->bindInt($stmt, 2, $grid->id);
			$stmt->execute();	
		}
		
		$q = "SELECT * FROM grids";
		$stmt = $pdo->query($q);
		$grids = $pdo->fetchAll($stmt, stdClass);
		dieItem($grids);
	}
	
	private function getModel($urlSite, $thumbW, $thumH=null)
	{
		$pdo = PDOThelia::getInstance();
	
		$query = "SELECT rd.titre, rd.rubrique as rubrique_id,
rc.caracteristique as caracteristique_id
FROM rubriquedesc as rd
JOIN rubcaracteristique as rc ON  rc.rubrique=rd.rubrique
JOIN caracteristiquedesc as c ON c.caracteristique=rc.caracteristique
ORDER BY rubrique_id";
	
		$result = $pdo->query($query);
		$result = $result->fetchAll(PDO::FETCH_OBJ);
		$rubCaraDict = array();
		foreach ($result as $carac)
		{
			$rubCaraDict[$carac->rubrique_id][] = $carac->caracteristique_id;
		}
		$query = "SELECT rd.rubrique as rubrique_id,
rc.caracteristique as caracteristique_id
FROM rubriquedesc as rd
JOIN rubcaracteristique as rc ON  rc.rubrique=rd.rubrique
JOIN caracteristiquedesc as c ON c.caracteristique=rc.caracteristique
ORDER BY rubrique_id";
		$result = $pdo->query($query);
		$result = $result->fetchAll(PDO::FETCH_OBJ);
	
		$query = "SELECT c.titre as caracteristiquedesc_titre, c.caracteristique as caracteristiquedesc_id, cd.id as caracdisp,
cdd.titre AS caracdispdesc_titre,
bo.size as size, bo.price as price, bo.id as id, bo.metal as metal
FROM caracteristiquedesc as c
JOIN caracdisp as cd ON c.caracteristique=cd.caracteristique
JOIN caracdispdesc as cdd ON cdd.caracdisp=cd.id
LEFT JOIN bo_carac AS bo ON bo.caracdisp=cdd.caracdisp
ORDER BY c.caracteristique, cdd.classement, cdd.titre, bo.metal, bo.size;";
	
		$result = $pdo->query($query);
		$result = $result->fetchAll(PDO::FETCH_OBJ);
		$caracteristiques = array();
		$model = XMLUtils::getDom("<model/>");
		$doc = $model->ownerDocument;
		$xp = new DOMXPath($doc);
	
		$caracModel = $doc->createElement("caracteristiques");
		$model->appendChild($caracModel);
	
		foreach ($result as $value)
		{
			$caracteristiques[$value->caracteristiquedesc_id][] = $value;
		}
		$bo_carac_props = array("size", "price", "id");
		foreach ($caracteristiques as $key => $value)
		{
			$caracteristique = $doc->createElement("caracteristique");
			$caracteristique->setAttribute("id", $key);
			$carac = $value[0];
			$caracteristique->setAttribute("label", $carac->caracteristiquedesc_titre);
			$caracModel->appendChild($caracteristique);
			foreach ($value as $row)
			{
				if(empty($row->id))
				{
					$carac = $doc->createElement("carac");
					$carac->setAttribute("label", $row->caracdispdesc_titre);
					$carac->setAttribute("caracdisp", $row->caracdisp);
					$caracteristique->appendChild($carac);
				}
				else
				{
					$id = $row->caracdisp;
					$caracdisp = $xp->query("carac[@caracdisp='$id']", $caracteristique);
					if($caracdisp->length)
					{
						$caracdisp = $caracdisp->item(0);
					}
					else
					{
						$caracdisp = $doc->createElement("carac");
						$caracdisp->setAttribute("caracdisp", $id);
						$caracdisp->setAttribute("label", $row->caracdispdesc_titre);
						$caracteristique->appendChild($caracdisp);
					}
					$metal = $xp->query("metal[@label='$row->metal']", $caracdisp);
					if($metal->length)
					{
						$metal = $metal->item(0);
					}
					else
					{
						$metal = $doc->createElement("metal");
						$metal->setAttribute("label", $row->metal);
						$caracdisp->appendChild($metal);
					}
					$bo_carac = $doc->createElement("bo_carac");
					foreach ($bo_carac_props as $p)
					{
						$bo_carac->setAttribute($p, $row->{$p});
					}
					$metal->appendChild($bo_carac);
				}
			}
		}
	
		$query = "SELECT c.caracdisp, c.caracteristique as carac,
img.fichier as src,
p.id AS produit_id, p.ref, p.nouveaute, p.classement AS produit_index,
pd.titre AS produit_label,
r.classement AS rubrique_index, r.id AS rubrique, r.parent, r.lien as type,
rd.titre AS rubrique_label
FROM rubrique AS r
LEFT JOIN rubriquedesc AS rd ON rd.rubrique=r.id
LEFT JOIN produit AS p ON r.id = p.rubrique
LEFT JOIN image as img ON p.id = img.produit AND img.classement = 1
LEFT JOIN produitdesc AS pd ON p.id=pd.produit
LEFT JOIN caracval AS c ON c.produit=p.id
ORDER BY r.parent, r.classement, p.classement;";
		$result = $pdo->query($query);
		$result = $result->fetchAll(PDO::FETCH_OBJ);
		$tree = $this->getModelTree($result, $doc, $urlSite, $rubCaraDict, $thumbW, $thumbH);
		$model->appendChild($tree);
		return $doc->saveXML();
	}
	
	/**
	 * @param unknown $rows
	 * @param DOMDocument $doc
	 * @return DOMElement
	 */
	private function getModelTree($rows, DOMDocument $doc, $urlSite, $rubCaraDict, $thumbW, $thumbH=null)
	{
		if(! $thumbH)
			$thumbH = $thumbW;
		$root = $doc->createElement("rubrique");
		$root->setAttribute("id", 0);
		$rubriques = array();
		$produits = array();
		foreach ($rows as $row)
		{
			$row->rubrique = intval($row->rubrique);
			if(! array_key_exists($row->rubrique, $rubriques))
			{
				$row->parent = intval($row->parent);
				$rubrique = $doc->createElement("rubrique");
				$rubrique->setAttribute("id", $row->rubrique);
				$rubrique->setAttribute("type", $row->type);
				$rubrique->setAttribute("parent", $row->parent);
				$rubrique->setAttribute("label", $row->rubrique_label);
				$rubrique->setAttribute("index", $row->rubrique_index);
				$rubCaracs = "";
				if(array_key_exists($row->rubrique, $rubCaraDict))
				{
					$rubCaracs = implode(",", $rubCaraDict[$row->rubrique]);
				}
				$rubrique->setAttribute("caracteristique_id", $rubCaracs);
				$rubriques[$row->rubrique] = $rubrique;
			}
			if(isset($row->produit_index))
			{
				$row->produit_id = intval($row->produit_id);
				$produit = $doc->createElement("produit");
				$produit->setAttribute("id", $row->produit_id);
				$produit->setAttribute("ref", $row->ref);
				try {
					$produit->setAttribute("src", 
							"$urlSite/image.php?produit=" . $row->produit_id. "&width=" . $thumbW . "&height=" . $thumbH
							);
							
				} catch (Exception $e) {
					$produit->setAttribute("src", "");
				}
				$produit->setAttribute("rubrique", $row->rubrique);
				$produit->setAttribute("index", $row->produit_index);
				$produit->setAttribute("label", $row->produit_label);
				$produit->setAttribute("caracdisp", $row->caracdisp);
				$produit->setAttribute("carac", $row->carac);
				$produit->setAttribute("new", $row->nouveaute);
	
				$produits[] = $produit;
			}
		}
		foreach ($rubriques as $id=>$rubrique)
		{
			$rubrique instanceof DOMElement;
			$parent_id = intval($rubrique->getAttribute("parent"));
			if($parent_id == 0)
				$parent = $root;
			else
				$parent = $rubriques[$parent_id];
			$parent instanceof DOMElement;
			$parent->appendChild($rubrique);
			$rubrique->removeAttribute("parent");
		}
		foreach($produits as $produit)
		{
			$produit instanceof DOMElement;
			$parent = $rubriques[strval($produit->getAttribute("rubrique"))];
			$parent->appendChild($produit);
			$produit->removeAttribute("rubrique");
		}
		return $root;
	}
}