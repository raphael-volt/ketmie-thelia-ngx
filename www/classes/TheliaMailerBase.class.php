<?php

require_once dirname(__DIR__) . '/lib/phpMailer/class.phpmailer.php';

class TheliaMailerBase extends PHPMailer
{
	
	public function hasArray($html=false, $text=false)
	{
		$result = array();
		$result["subject"] = $this->_getSubject();
		$result["from"] = $this->From;
		$result["to"] = $this->toAddresses;
		if($html)
			$result["html"] = $this->messageDesc->description;
		if($text)
			$result["text"] = $this->messageDesc->descriptiontext;
		return $result;
	}
	private $toAddresses = array();
	public function AddAddress($address, $name = '') 
	{
		if(array_search($address, $this->toAddresses) === false)
			$this->toAddresses[] = $address;
		
		
		return parent::AddAddress($address, $name);
	}
	protected $debug=false;
	/**
	 * @var Client
	 */
	protected $client;
	protected $nomsite;
	protected $urlsite;
	protected $htmlTemplate;
	/**
	 * @var Messagedesc
	 */
	protected $messageDesc;
	
	/**
	 * @var PDOThelia
	 */
	protected $pdo;
	 
	/**
	 * @param string $messageNom
	 * @return Messagedesc
	 */
	protected function getMessageDescById($id)
	{
		
	}
	protected $_mysubject="";
	protected function _getSubject()
	{
		return $this->_mysubject;
	}
	
	protected function getMessageDesc($messageNom)
	{
		$query = "SELECT md.* FROM ". Messagedesc::TABLE . " as md
LEFT JOIN ". Message::TABLE . " as m ON m.id = md.message
WHERE m.nom=?";
		$stmt = $this->pdo->prepare($query);
		$this->pdo->bindString($stmt, 1, $messageNom);
		$stmt->execute();
		if($stmt->rowCount())
		{
			$messageDesc = $this->pdo->fetch($stmt, Messagedesc);
			$messageDesc instanceof Messagedesc;
			return $messageDesc;
		}
		return null;
	}
	function __construct(Client $client, $messageNom="")
	{
		parent::__construct();
		
		$this->pdo = PDOThelia::getInstance();
		$this->debug = intval(Variable::lire("proddebug")) ? true:false;
		$this->client = $client;
		$this->nomsite = Variable::lire("nomsite");
		$this->urlsite = Variable::lire("urlsite");
		$mailtemplateid = Variable::lire("mailtemplateid");
		
		$query = "SELECT description FROM " . Messagedesc::TABLE . " WHERE message=?";
		$stmt = $this->pdo->prepare($query);
		$this->pdo->bindInt($stmt, 1, Variable::lire("mailtemplateid"));
		$stmt->execute();
		$this->htmlTemplate = $this->pdo->fetchColumn($stmt);
		
		if($messageNom != "")
		{
			$this->messageDesc = $this->getMessageDesc($messageNom);
		}
		$this->throwExceptionIfMessagedescIsNull();
		$this->_mysubject = $this->substiSite($this->messageDesc->intitule);
		
	}
	
	private function throwExceptionIfMessagedescIsNull()
	{
		if( ! $this->messageDesc)
		{
			throw new Exception("Messagedesc instance is null");
		}
	}
	
	protected function replaceVariables()
	{
		$this->throwExceptionIfMessagedescIsNull();
		$this->htmlTemplate = str_replace("__TITLE__", $this->messageDesc->titre, $this->htmlTemplate);
		
		$this->messageDesc->description = $this->replaceDefaultVariables($this->messageDesc->description);
		
		$this->messageDesc->descriptiontext = $this->replaceDefaultVariables($this->messageDesc->descriptiontext);
		
		$this->createCSS();
	}
	
	protected function replaceDefaultVariables($input)
	{
		$input = $this->substiAcount($input);
		$input = $this->substiClient($input);
		return $this->substiSite($input);
	}
	
	protected function createCSS()
	{
		$this->messageDesc->description = TheliaMailCSSController::classToInlineCSS($this->messageDesc->description);
	}
	
	protected function setupMail()
	{
		$this->throwExceptionIfMessagedescIsNull();
		/*
		 //#PARAM_FOND_securise=1 #PARAM_FOND_reset=1 #PARAM_FOND_nopageret=1
		 $smtp = new Smtpconfig(1);
		 if($smtp->active)
		 {
		 $this->IsSMTP();
		
		 $this->Host = $smtp->serveur;
		 $this->Port = $smtp->port;
		 $this->SMTPAuth = true;
		 $this->Username = $smtp->username;
		 $this->Password = $smtp->password;
		 }
		 else
		 	$this->IsMail();
		 	// die("<pre>" .  print_r($smtp, true));
		 	*/
		
		$client = $this->client;
		$this->IsMail();
		$this->CharSet = "UTF-8";
		$this->FromName = $this->nomsite;
		$this->From = Variable::lire("emailfrom");
		$this->Subject = $this->_getSubject();
		$this->AddAddress($client->email, $client->prenom . " " . $client->nom);
		$this->replaceVariables();
		$this->htmlTemplate = str_replace("__MESSAGE__", $this->messageDesc->description, $this->htmlTemplate);
		$this->MsgHTML($this->htmlTemplate);
		$this->AltBody = $this->messageDesc->descriptiontext;
	}
	
	public function Send()
	{
		try 
		{
			$this->setupMail();
			if($this->debug)
				return $this->hasArray(true);
			return parent::Send();
		} 
		catch (Exception $e) 
		{
			return $e;
		}
		return null;
	}
	
	protected function substiSite($input)
	{
		$input = str_replace("__LOGO_SITE__", $this->getSiteLogo(), $input);
		$input = str_replace("__NOMSITE__", $this->nomsite, $input);
		$input = str_replace("__URLSITE__", $this->urlsite, $input);
		return $input;
	}
	
	protected function substiAcount($input)
	{
		$input = str_replace("__EMAIL__", $this->client->email, $input);
		$input = str_replace("__MOTDEPASSE__", $this->client->motdepasse, $input);
		return $input;
	}
	
	protected function substiClient($input)
	{
		$input = str_replace("__CIVILITE__", $this->getClientRaison(), $input);
		$input = str_replace("__NOM__", $this->client->nom, $input);
		$input = str_replace("__PRENOM__", $this->client->prenom, $input);
		$input = str_replace("__ADRESSE__", $this->getClientAddress(), $input);
		
		$input = str_replace("__CPOSTAL__", $this->client->cpostal, $input);
		$input = str_replace("__VILLE__", $this->client->ville, $input);
		
		$input = str_replace("__TELEPHONE__", $this->getClientTel(), $input);
		$input = str_replace("__PAYS__", $this->getClientPays(), $input);
		return $input;
	}
	
	private $logoUrl;
	
	protected function getSiteLogo()
	{
		if($this->logoUrl)
			return $this->logoUrl;
		
		$imd = new Imagedesc();
		$im = new Image();
		$f = $imd->query("SELECT fichier as f FROM $im->table as i
LEFT JOIN $imd->table as id ON id.image = i.id
WHERE id.titre='logosite'");
		$f = $imd->fetch_object($f);
		$this->logoUrl = $this->urlsite ."/" . redim("contenu", $f->f, 400);
		return $this->logoUrl;
	}
	
	protected function getDeliveryAddress(Adresse $address, $lineBreak="<br/>")
	{
		return $this->getAddress(
				$address->adresse1, 
				$address->adresse2, 
				$address->adresse3, 
				$lineBreak);
	}
	
	protected function getClientAddress($lineBreak="<br/>")
	{
		return $this->getAddress(
				$this->client->adresse1, 
				$this->client->adresse2, 
				$this->client->adresse3, 
				$lineBreak);
	}
	
	protected function getAddress($address1, $address2, $address3, $lineBreak)
	{
		$address = array();
		foreach (array($address1, $address2, $address3) as $value) 
		{
			if(strlen($value))
				$address[] = $value;
		}
		return implode($lineBreak, $address);
	}
	
	protected function getDeliveryRaison(Adresse $address)
	{
		return $this->getRaison($address->raison);
	}
	
	protected function getClientRaison()
	{
		return $this->getRaison($this->client->raison);
	}
	
	protected function getRaison($raison)
	{
		$raison = new Raisondesc($raison);
		return $raison->long;
	}
	
	protected function getClientPays()
	{
		return $this->getPays($this->client->pays);
	}
	
	protected function getDeliveryPays(Adresse $address)
	{
		return $this->getPays($address->pays);
	}
	
	protected function getPays($pays)
	{
		$pays = new Paysdesc($pays);
		return $pays->titre;
	}
	
	protected function getClientTel($separator=" | ")
	{
		return $this->getTel(
				$this->client->telfixe, 
				$this->client->telport, $separator);
	}
	
	private function getTel($telFixe, $telPort, $separator)
	{
		$tel = array();
		foreach (array($telFixe, $telPort) as $value) 
		{
			if(strlen($value))
			{
				$tel[] = $value;
			}
		}
		return implode($separator, $tel);
	}
}