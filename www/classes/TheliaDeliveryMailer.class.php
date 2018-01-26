<?php

class TheliaDeliveryMailer extends TheliaMailerBase
{
	private static $callStatic = false;
	
	public static function sendMails(Commande $commande)
	{
		self::$callStatic = true;
		$mail = new TheliaDeliveryMailer($commande);
		self::$callStatic = false;
		$res = array();
		$res["client"] = $mail->Send();
		$url = urlfond($mail->fond);
		
		self::$callStatic = true;
		$mail = new TheliaAdminMailer($commande);
		self::$callStatic = false;
		
		$res["admin"] = $mail->Send();
		if(intval(Variable::lire("proddebug")) == 1)
		{
			printInLog($res);
		}
		ActionsModules::instance()->appel_module("confirmation", $commande);
		$_SESSION["navig"]->panier = new Panier();
		session_write_close();
		header("Location: " . $url);
		exit;
	}
	/**
	 * @var Commande
	 */
	private $commande;
	/**
	 * @var Messagedesc
	 */
	private $paiementDesc;
	
	public function __construct(Commande $commande, $messageNom="mailconfirmcli")
	{
		if(self::$callStatic == false)
			throw new Exception("static implementation");
		$this->commande = $commande;
		$client = new Client($commande->client);
		parent::__construct($client, $messageNom);
		$this->_mysubject = "$this->nomsite | commande n° {$this->commande->ref}";
	}

	protected function createCSS()
	{
		$thumsize = intval(Variable::lire("thumbsize"));
		$rowsize = $thumsize + 10;
		
		TheliaMailCSSController::addStyle("table-detail", "margin:0 0 8px 0; border:1px solid #333333;border-collapse: collapse");
		TheliaMailCSSController::addStyle("detail-head", "background-color: #DDDDDD; border:1px solid #333333;");
		TheliaMailCSSController::addStyle("detail-th", "padding: 4px; border:1px solid #333333;");
		TheliaMailCSSController::addStyle("cellprod-img", "width:{$rowsize}px; height:{$rowsize}px; border:1px solid #333333;border-right:none;text-align:center;");
		TheliaMailCSSController::addStyle("cellprod-title", "border:1px solid #333333;border-left:none; padding:4px; vertical-align: top;");
		TheliaMailCSSController::addStyle("cellprod-ref", "border:1px solid #333333; padding:4px; vertical-align: top;");
		TheliaMailCSSController::addStyle("cellprod", "text-align: right; border:1px solid #333333; padding:4px; vertical-align: top;");
		TheliaMailCSSController::addStyle("cellprod-right", "text-align: right; padding:4px;border-right:1px solid #333333;");
		TheliaMailCSSController::addStyle("cellprod-right-last", "text-align: right; padding:4px;border:1px solid #333333; background-color: #F0F0F0;");
		
		parent::createCSS();
	}
	
	protected $clientPays;
	protected $clientRaison;
	protected $commandeDate;
	protected $livAddressRaison;
	protected $livAddressPays;
	
	public $fond="";
	protected function replaceVariables()
	{
		$commande = $this->commande;
		
		$jour = substr($commande->date, 8, 2);
		$mois = substr($commande->date, 5, 2);
		$annee = substr($commande->date, 0, 4);
		
		$heure = substr($commande->date, 11, 2);
		$minute = substr($commande->date, 14, 2);
		$seconde = substr($commande->date, 17, 2);
		$this->commandeDate = array($jour, $mois, $annee, $heure, $minute, $seconde);
		$paiement = new Modules($commande->paiement);
		$paiementdesc = new Modulesdesc($paiement->nom, $commande->lang);
		
		$query = "SELECT nom FROM modules WHERE id=?";
		$stmt = $this->pdo->prepare($query);
		$this->pdo->bindInt($stmt, 1, $commande->paiement);
		$this->fond = $this->pdo->fetchColumn($stmt, 0, true);
		
		$messageNom = "{$this->fond}-info";
		
		$this->paiementDesc = $this->getMessageDesc($messageNom);
		
		$transport = new Modules($commande->transport);
		$transportdesc = new Modulesdesc($transport->nom, $commande->lang);
		
		$adresse = new Venteadr($commande->adrlivr);
		$this->livAddressRaison = $this->getRaison($adresse->raison);
		$this->livAddressPays = $this->getPays($adresse->pays);
		
		$query = "SELECT * FROM " . Venteprod::TABLE. " WHERE commande=?";
		$venteprods = $this->pdo->prepare($query);
		$this->pdo->bindInt($venteprods, 1, $commande->id);
		$venteprods = $this->pdo->fetchAll($venteprods, Venteprod, true);
		$clientRaison = $this->getClientRaison();
		
		if($this->paiementDesc)
		{
			
			$this->messageDesc->description = str_replace("__PAIEMENT_INFO__", $this->paiementDesc->description, $this->messageDesc->description);
			$this->messageDesc->descriptiontext = str_replace("__PAIEMENT_INFO__", $this->paiementDesc->descriptiontext, $this->messageDesc->descriptiontext);
		}
		
		parent::replaceVariables();
		
		$total = $commande->total();
		$totcmdport = $commande->port + $total;
		
		$this->messageDesc->description = $this->substiCommande(
				$this->messageDesc->description,
				$commande, $adresse, $paiementdesc->titre, $transportdesc->titre, 
				$venteprods, $total, $totcmdport);
		
		$this->messageDesc->descriptiontext = $this->substiCommande(
				$this->messageDesc->descriptiontext, 
				$commande, $adresse, $paiementdesc->titre, $transportdesc->titre, 
				$venteprods, $total, $totcmdport, false);
		
	}
	private function substiCommande($corps, Commande $commande, Venteadr $address, $paiementTitre, $transaction, $venteProds, $total, $totcmdport, $isHtml=true)
	{
		$thumsize = intval(Variable::lire("thumbsize"));
		
		$corps = str_replace("__COMMANDE_REF__", $commande->ref, $corps);
		$corps = str_replace("__COMMANDE_DATE__", $this->commandeDate[0] . "/" . $this->commandeDate[1] . "/" . $this->commandeDate[2], $corps);
		$corps = str_replace("__COMMANDE_HEURE__", $this->commandeDate[3] . ":" . $this->commandeDate[4], $corps);
		$corps = str_replace("__COMMANDE_TRANSACTION__", $transaction, $corps);
		$corps = str_replace("__COMMANDE_PAIEMENT__", $paiementTitre, $corps);
		$corps = str_replace("__COMMANDE_TOTALPORT__", $totcmdport-$commande->remise, $corps);
		$corps = str_replace("__COMMANDE_TOTAL__", $total, $corps);
		$corps = str_replace("__COMMANDE_PORT__", $commande->port, $corps);
		$corps = str_replace("__COMMANDE_REMISE__", $commande->remise, $corps);
		$corps = str_replace("__COMMANDE_TRANSPORT__", $transaction, $corps);
		$corps = str_replace("__COMMANDE_LIVRRAISON__", $this->livAddressRaison, $corps);
		$corps = str_replace("__COMMANDE_LIVRNOM__", $address->nom, $corps);
		$corps = str_replace("__COMMANDE_LIVRPRENOM__", $address->prenom, $corps);
		$corps = str_replace("__ADRESSE_LIVRAISON__", $this->getAddress($address->adresse1, $address->adresse2, $address->adresse3, $isHtml ? "<br/>":"\n"), $corps);
		$corps = str_replace("__COMMANDE_LIVRCPOSTAL__", $address->cpostal, $corps);
		$corps = str_replace("__COMMANDE_LIVRVILLE__", $address->ville, $corps);
		$corps = str_replace("__COMMANDE_LIVRPAYS__", $this->livAddressPays, $corps);
		$corps = str_replace("__COMMANDE_LIVRTEL__", $address->tel, $corps);
		$corps = str_replace("__CLIENT_REF__", $this->client->ref, $corps);
		
		$corps = str_replace("__CLIENT_REF__", $client->ref, $corps);
		$corps = str_replace("::euro::", "€", $corps);
		$pattern = '{<VENTEPROD>((?:(?:(?!<VENTEPROD[^>]*>|</VENTEPROD>).)++|<VENTEPROD[^>]*>(?1)</VENTEPROD>)*)</VENTEPROD>}si';
		
		if (preg_match($pattern, $corps, $cut)) 
		{
			$corps = str_replace("<VENTEPROD>", "", $corps);
			$corps = str_replace("</VENTEPROD>", "", $corps);
		
			$res="";
		
			if($isHtml)
			{
				$q = "SELECT m.fichier FROM " . Produit::TABLE . " as p
LEFT JOIN " . Image::TABLE . " AS m ON m.produit=p.id AND m.classement=1
WHERE p.ref=?;";
				$pdo = $this->pdo;
				$stmt = $pdo->prepare($q);
			}
				
			foreach($venteProds as $row)
			{
				$row instanceof Venteprod;
				if($isHtml)
				{
					$pdo->bindString($stmt, 1, $row->ref);
					$url = $pdo->fetchColumn($stmt, 0, true);
					$url = redim("produit", $url, $thumsize, $thumsize);
					$temp = str_replace("__VENTEPROD_URL__", $url, $cut[1]);
					$temp = str_replace("__VENTEPROD_TITRE__", $row->titre, $temp);
				}
				else 
					$temp = str_replace("__VENTEPROD_TITRE__", $row->titre, $cut[1]);
					
				$temp =  str_replace("__VENTEPROD_REF__", $row->ref, $temp);
				$temp =  str_replace("__VENTEPROD_CHAPO__", $row->chapo, $temp);
				$temp =  str_replace("__VENTEPROD_QUANTITE__", $row->quantite, $temp);
				$temp =  str_replace("__VENTEPROD_PRIXU__", $row->prixu, $temp);
				$temp =  str_replace("__VENTEPROD_TOTAL__", $row->prixu * $row->quantite, $temp);
		
				$res .= $temp;
			}
		
			$corps = str_replace($cut[1], $res, $corps);
			
		}
		return $corps;
	}
}