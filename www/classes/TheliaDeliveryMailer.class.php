<?php

class TheliaDeliveryMailer extends TheliaMailerBase
{
	private static $callStatic = false;
	private function getLivraisonTextTemplate() {
	    return <<<EOL
Livraison
-------------------------------------------------------
Transporteur : __COMMANDE_TRANSPORT__
Nom : __COMMANDE_LIVRRAISON__ __COMMANDE_LIVRPRENOM__ __COMMANDE_LIVRNOM__
Adresse : __ADRESSE_LIVRAISON__
__COMMANDE_LIVRCPOSTAL__ __COMMANDE_LIVRVILLE__
Pays : __COMMANDE_LIVRPAYS__
=======================================================
EOL;
	    
	}
	private function getLivraisonHtmlTemplate() {
	    return <<<EOL
        <table style="border:1px solid #333333; margin:0 0 8px 0; padding:0; border-spacing:4px">
            <caption style="text-align:left; white-space:nowrap; font-size:14px; margin:0 0 4px 0">
                <h3>Livraison</h3>
            </caption>
            <tbody>
                <tr>
                    <td style="font-weight:initial; text-align:right; vertical-align:top;">Transporteur : </td>
                    <td style="font-weight:bold">__COMMANDE_TRANSPORT__</td>
                </tr>
                <tr>
                    <td style="font-weight:initial; text-align:right; vertical-align:top;">Nom : </td>
                    <td style="font-weight:bold">__COMMANDE_LIVRRAISON__ __COMMANDE_LIVRPRENOM__ __COMMANDE_LIVRNOM__</td>
                </tr>
                <tr>
                    <td style="font-weight:initial; text-align:right; vertical-align:top;">Adresse : </td>
                    <td style="font-weight:bold">__ADRESSE_LIVRAISON__<br />__COMMANDE_LIVRCPOSTAL__ __COMMANDE_LIVRVILLE__</td>
                </tr>
                <tr>
                    <td style="font-weight:initial; text-align:right; vertical-align:top;">Pays : </td>
                    <td style="font-weight:bold">__COMMANDE_LIVRPAYS__</td>
                </tr>
            </tbody>
        </table>
EOL;
	}
	
	public static function sendMails(Commande $commande)
	{
		self::$callStatic = true;
		$mail = new TheliaDeliveryMailer($commande);
		self::$callStatic = false;
		$mail->Send();
		$url = urlfond($mail->fond);
		
		self::$callStatic = true;
		$mail = new TheliaAdminMailer($commande);
		self::$callStatic = false;
		
		$mail->Send();
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
		$this->_mailSubject = $this->nomsite . " | commande " . $this->commande->ref;
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
		$venteprods = $this->pdo->fetchAll($venteprods, Venteprod::class, true);
		$clientRaison = $this->getClientRaison();
	   
		if($transport->nom != "retraitatelier") {
		    $this->htmlTemplate = str_replace("__TRANSPORT__", $this->getLivraisonHtmlTemplate(), $this->htmlTemplate);
		    $this->textTemplate = str_replace("__TRANSPORT__", $this->getLivraisonTextTemplate(), $this->textTemplate);
		}
		if($this->paiementDesc)
		{
		    $this->htmlTemplate = str_replace("__PAIEMENT_INFO__", $this->paiementDesc->description, $this->htmlTemplate);
		    $this->textTemplate = str_replace("__PAIEMENT_INFO__", $this->paiementDesc->descriptiontext, $this->textTemplate);
		}
		
		parent::replaceVariables();
		
		$total = $commande->total();
		$totcmdport = $commande->port + $total;
		
		$this->htmlTemplate = $this->substiCommande(
		        $this->htmlTemplate,
				$commande, $adresse, $paiementdesc->titre, $transportdesc->titre, 
				$venteprods, $total, $totcmdport);
		
		$this->textTemplate = $this->substiCommande(
		        $this->textTemplate, 
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
		$corps = str_replace("__ADRESSE_LIVRAISON__", $this->getAddress($address->adresse1, $address->adresse2, $address->adresse3, $isHtml ? "<br/>":"\r\n"), $corps);
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
					$url = "$this->urlsite/$url";
					$temp = str_replace("__VENTEPROD_URL__", $url, $cut[1]);
					$temp = str_replace("__VENTEPROD_TITRE__", $row->titre, $temp);
				}
				else 
				    $temp = str_replace("__VENTEPROD_TITRE__", $this->br2nl($row->titre), $cut[1]);
					
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
	private function br2nl($data) {
	    return preg_replace("/\<br\s*\/?\>/i", "\n", $data);
	}
}