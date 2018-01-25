<?php 
class AdminAbro
{
	public function AdminAbro()
	{
		
	}
	
	public function createMenu()
	{
		$pdo = PDOThelia::getInstance();
		$query = "SELECT d.titre as label, r.id, r.parent as pid, r.classement AS child_index FROM rubrique AS r
LEFT JOIN rubriquedesc AS d ON d.rubrique=r.id
ORDER BY parent, child_index";
	
		$result = $pdo->query($query);
		$result = $result->fetchAll(PDO::FETCH_CLASS, RubDesc::class);
		$query = "SELECT d.titre as label, p.id, p.rubrique, p.ref FROM " . Produit::TABLE . " as p
LEFT JOIN " . Produitdesc::TABLE . " as d ON p.id=d.produit
ORDER BY rubrique, classement";
		$produits = $pdo->query($query);
		$produits = $produits->fetchAll(PDO::FETCH_CLASS, ProdDesc::class);
		$prodDict = array();
		foreach ($produits as $prod)
		{
			$prod instanceof ProdDesc;
			$prodDict[$prod->rubrique][] = $prod;
		}
		$root = new RubDesc();
		$root->id = 0;
		$root->pid = 0;
		$rubs = array(0=>$root);
		
		foreach ($result as $r)
		{
			$rubs[$r->id] = $r;
		}
		foreach ($rubs as $id => $r)
		{
			$r instanceof RubDesc;
			if($r == $root)
				continue;
			$p = $rubs[$r->pid];
			$p instanceof RubDesc;
			$r->parent = $p;
			$i = $r->child_index -1;
			$p->children[$i] = $r;
		}
		foreach ($rubs as $id => $r)
		{
			$r instanceof RubDesc;
			if($r == $root)
				continue;
			if(array_key_exists($r->id, $prodDict))
			{
				$r->children = array_merge($r->children, $prodDict[$r->id]);
			}
		}
		
		$doc instanceof DOMDocument;
		$ul = XMLUtils::getDom("<ul id=\"arbomenu\"/>");
		$doc = $ul->ownerDocument;
		
		$li = $ul->appendChild($doc->createElement("li"));
		$a = $li->appendChild($doc->createElement("a", "ARBO"));
		$ul2 = $li->appendChild($doc->createElement("ul"));
		$ul2->setAttribute("style", "display:none");
		$rubs = $root->getRubChildren();
		foreach ($rubs as $r)
		{
			$r instanceof RubDesc;
			$this->rubRecurse($r, $ul2, $doc);
		}
	
		return $doc->saveXML($ul);
	}
	
	
	private function getRubriqueLien($parent)
	{
		return "parcourir.php?parent={$parent}";
	}
	private function getProduitLien($ref, $rubrique)
	{
		return "produit_modifier.php?ref={$ref}&rubrique={$rubrique}";
	}
	
	private function rubRecurse(RubDesc $rub, DOMElement $node, DOMDocument $doc)
	{
		$li = $doc->createElement("li");
		$url = $this->getRubriqueLien($rub->id);
		$a = $doc->createElement("a", $rub->label);
		$a->setAttribute("href", $url);	
		$li->appendChild($a);
		$node->appendChild($li);
		$rubs = $rub->getRubChildren();
		$n = count($rubs);
		$ul = null;
		if($n)
		{
			$ul = $doc->createElement("ul");
			$li->appendChild($ul);
			foreach ($rubs as $r)
			{
				$this->rubRecurse($r, $ul, $doc);
			}
		}
		$prods = $rub->getProdChildren();
		$n = count($prods);
		if($n)
		{
			if($ul == null)
			{
				$ul = $doc->createElement("ul");
				$li->appendChild($ul);
				
			}
			$ul->setAttribute("class", "max300");
			foreach ($prods as $p)
			{
				$p instanceof ProdDesc;
				$li = $ul->appendChild($doc->createElement("li"));
				$a = $li->appendChild($doc->createElement("a", $p->label));
				$a->setAttribute("href", $this->getProduitLien($p->ref, $p->rubrique));
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
}
function createArbo()
{
	$arbo = new AdminAbro();
	echo $arbo->createMenu();
}

?>

<div id="entete">
	<div class="logo">
		<a href="accueil.php"><img src="gfx/thelia_logo.jpg" alt="THELIA solution e-commerce" /></a>
	</div>
	<dl class="Blocmoncompte">
		<dt><a href="index.php?action=deconnexion" ><?php echo trad('Deconnexion', 'admin'); ?></a></dt><dt> | </dt><dt><strong><?php echo($_SESSION["util"]->prenom); ?> <?php echo($_SESSION["util"]->nom); ?></strong> </dt>
	</dl>
	<div class="Blocversion">V <?php echo rtrim(preg_replace("/(.)/", "$1.", Variable::lire('version')), "."); ?></div>
</div>

<div id="menuGeneral">
	<div>
   		<ul id="menu">
	        <li><a href="accueil.php" <?php if($menu == "accueil") { ?>class="selected"<?php } ?>><?php echo trad('Accueil', 'admin'); ?></a></li>
	    </ul>
	   <ul class="separation_menu"><li>&nbsp;</li></ul>

	  <?php	if(est_autorise("acces_clients")){ ?>
	        <ul id="menu1">
		        <li><a href="client.php" <?php if($menu == "client") { ?>class="selected"<?php } ?>><?php echo trad('Clients', 'admin'); ?></a></li>
	        </ul>
	        <ul class="separation_menu"><li>&nbsp;</li></ul>
	  <?php } ?>
	  <?php	if(est_autorise("acces_commandes")){ ?>
	        <ul id="menu2">
  	        <li><a href="commande.php" <?php if($menu == "commande") { ?>class="selected"<?php } ?>><?php echo trad('Commandes', 'admin'); ?></a></li>
            </ul>
            <ul class="separation_menu"><li>&nbsp;</li></ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_catalogue")){ ?>
             <ul id="menu3">
            <li><a href="parcourir.php" <?php if($menu == "catalogue") { ?>class="selected"<?php } ?>><?php echo trad('Catalogue', 'admin'); ?></a></li>
            </ul>
            <ul class="separation_menu"><li>&nbsp;</li></ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_contenu")){ ?>
             <ul id="menu4">
            <li><a href="listdos.php" <?php if($menu == "contenu") { ?>class="selected"<?php } ?>><?php echo trad('Contenu', 'admin'); ?></a></li>
            </ul>
            <ul class="separation_menu"><li>&nbsp;</li></ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_codespromos")){ ?>
             <ul id="menu5">
            <li><a href="promo.php" <?php if($menu == "paiement") { ?>class="selected"<?php } ?>><?php echo trad('Codes_promos', 'admin'); ?></a></li>
            </ul>
            <ul class="separation_menu"><li>&nbsp;</li></ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_configuration")){ ?>
             <ul id="menu6">
            <li><a href="configuration.php" <?php if($menu == "configuration") { ?>class="selected"<?php } ?>><?php echo trad('Configuration', 'admin'); ?></a></li>
            </ul>
		  <?php } ?>
		  <?php	if(est_autorise("acces_modules")){ ?>
            <ul class="separation_menu"><li>&nbsp;</li></ul>
            <ul id="menu7">
			<li><a href="module_liste.php" <?php if($menu == "plugins") { ?>class="selected"<?php } ?>><?php echo trad('Modules', 'admin'); ?></a></li>
			</ul>
			<ul class="separation_menu"><li>&nbsp;</li></ul>
		  <?php } ?>
      	</div>
		  <?php	if(est_autorise("acces_rechercher")){ ?>
            <div id="moteur_recherche">
             <form action="recherche.php" method="post">
              <div class="bouton_recherche">
	         	<input type="image" src="gfx/icone_recherche.jpg" alt="Valider la recherche" />
	         </div>
             <div class="champs_recherche">
	         	<input type="text" name="motcle" value="<?php echo trad('Rechercher', 'admin'); ?>" class="zonerecherche" onClick="this.value=''" size="14" />
	         </div>

             </form>
            </div>
           <?php } ?>
</div>
<div id="treemenu">
<?php createArbo();?>
</div>