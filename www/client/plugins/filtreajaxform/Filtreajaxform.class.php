<?php

require_once(dirname(realpath(__FILE__)) . '/../../../classes/filtres/FiltreCustomBase.class.php');

class ParamsUtils
{
	public static function setupObject($target, $params)
	{
		foreach ($params as $name) 
		{
			$target->{$name} = strip_tags($_POST[$name]);
		}
	}
}

class UpdateAccountParams
{
	public static $params = array(
				"client_id", 
				"email1",
				"email2",
				"motdepasse1",
				"motdepasse2");
	
	public $client_id;
	public $email1="";
	public $email2="";
	public $motdepasse1="";
	public $motdepasse2="";
	
	public function __construct()
	{
		ParamsUtils::setupObject($this, self::$params);
	}
}

class AddressProxy
{
	public static $params = array(
			"libelle", 
			"raison", 
			"prenom", 
			"nom",
			"adresse1", 
			"cpostal", 
			"ville", 
			"tel", 
			"pays",
			"entreprise", 
			"adresse2", 
			"adresse3");
	
	public $libelle = "";
	public $raison = "";
	public $prenom = "";
	public $nom = "";
	public $adresse1 = "";
	public $cpostal = "";
	public $ville = "";
	public $tel = "";
	public $pays = "";
	public $entreprise = "";
	public $adresse2 = "";
	public $adresse3 = "";
	
	public function __construct($params=null)
	{
		if($params == null)
			$params = self::$params;
	
		ParamsUtils::setupObject($this, $params);
	}
	public static function getRequestedParameters()
	{
		return array_slice(self::$params, 0, 9);
	}
	/**
	 * @return Adresse
	 */
	public function asAdresse($id=0)
	{
		$address = new Adresse($id);
		foreach (self::$params as $prop)
		{
			$address->{$prop} = $this->{$prop};
		}
		return $address;
	}
}

class ClienProxy
{
	public static $params = array(
			"raison", 
			"prenom", 
			"nom",
			"adresse1", 
			"cpostal", 
			"ville", 
			"telfixe", 
			"pays",
			"adresse2", 
			"adresse3", 
			"telport",
			"entreprise", 
			"siret", 
			"intracom",
			"email1", 
			"email2", 
			"motdepasse1", 
			"motdepasse2");
	
	public $raison = "";
	public $prenom = "";
	public $nom = "";
	public $adresse1 = "";
	public $cpostal = "";
	public $ville = "";
	public $telfixe = "";
	public $telport = "";
	public $pays = "";
	public $adresse2 = "";
	public $adresse3 = "";
	public $entreprise = "";
	public $siret = "";
	public $intracom = "";
	public $email1 = "";
	public $email2 = "";
	public $motdepasse1 = "";
	public $motdepasse2 = "";
	
	public static function getRequestedParameters()
	{
		return array_slice(self::$params, 0, 11);
	}
	public function __construct($params=null)
	{
		if($params == null)
			$params = self::$params;
		
		ParamsUtils::setupObject($this, $params);
	}
	/**
	 * @return Client
	 */
	public function asClient()
	{
		$client = new Client();
		for ($i = 0; $i < 14; $i++)
		{
			$prop = self::$params[$i];
			$client->{$prop} = $this->{$prop};
		}
		$client->email = $this->email1;
		$client->motdepasse = $this->motdepasse1;
		
		return $client;
	}
	public function updateClient(Client $target)
	{
		$properties = self::getRequestedParameters();
		foreach ($properties as $prop) 
		{
			$target->{$prop} = $this->{$prop};
		}
	}
}

class Filtreajaxform extends FiltreCustomBase {

	const FAULT = 0;
	const SUCCESS = 1;
	const ACCESS_DENIED = 2;
	const MISSING_PARAMETER = 3;
	const UNAVAILABLE_EMAIL = 4;
	const INVALID_EMAIL = 5;
	const INVALID_PASSWORD = 6;
	const INVALID_PARAMETER = 7;
	const UNCHANGED = 8;
	const PHONE_REQUIRED = 9;
	const UNKNOWN_ACTION = 10;
	
	private $clientId;
	
	public function __construct()
	{
		parent::__construct("`\#FILTRE_ajaxform\(([^\|]+)\|\|([^\)]+)\)`");
	}
	
	public function calcule($match)
	{
		$match = parent::calcule($match);
		
		if( ! $this->paramsUtil->action || ! $this->paramsUtil->getNumParameters())
		{
			return self::FAULT;
		}
		
		$action = $this->paramsUtil->action;
		
		switch ($action) 
		{
			case "jqId":
			{
				if(count($match))
					return "#" . $match[0];
				return "";
				
			}
			case "urlGet":
			{
				$url = $values[0];
				$url = preg_replace("/&amp;/", "&", $url);
				$url = explode("?", $url);
				$inputs = array();
				if(count($url) > 1)
				{
					$url = explode("&", $url[1]);
					if(count($url))
					{
						foreach ($url as $val)
						{
							$val = explode("=", $val);
							$n = $val[0];
							if($n == "panier")
								continue;
							$val = $val[1];
							$inputs[] = <<<EOL
										<input type="hidden" name="$n" value="$val"/>
EOL;
						}
						return implode("\n", $inputs);
					}
				}
				return "";
				break;
			}
			case "securise":
			{
				if($action == "securise")
				{
					if(FOND_SECURISE && $this->paramsUtil->getNumParameters() > 1)
						return $values[1];
					
					if($this->paramsUtil->getNumParameters())
						return $values[0];
					return "";
				}
				break;
			}
			
			case "adresse":
			{
				if(isset($_POST[$action]))
					return $_POST[$action];
				return "";
			}
			
		}
		if(method_exists($this, $action))
		{
			if($action != "ajaxconnexion" && $action != "creercompte")
			{
				if(isset($_SESSION["navig"])
					&& property_exists($_SESSION["navig"], "client"))
					$this->clientId = (int) $_SESSION['navig']->client->id;
				if( ! $this->validateClientId())
					return self::ACCESS_DENIED . " validateClientId FAULT ($action)";
			}
			
			return $this->{$action}();
		}
		return self::UNKNOWN_ACTION;
	}
	
	protected function ajaxconnexion()
	{
		if( ! $this->validateRequestedParams(array("email", "motdepasse")))
			return self::MISSING_PARAMETER;
		
		$client = new Client();
		if ($client->charger($_POST["email"], $_POST["motdepasse"])) {
	
			$_SESSION['navig']->client = $client;
			$_SESSION['navig']->connecte = 1;
				
			ActionsModules::instance()->appel_module("apresconnexion", $client);
				
			return self::SUCCESS;
		}
		return self::ACCESS_DENIED;
	}
	
	protected function creercompte()
	{
		if( ! $this->validateRequestedParams(ClienProxy::$params))
			return self::MISSING_PARAMETER . "\n" . $this->getMissingParams(ClienProxy::$params);
		
		$param = new ClienProxy();
		
		if( ! filter_var($param->email1, FILTER_VALIDATE_EMAIL)
				|| $param->email1 != $param->email2)
			return self::INVALID_EMAIL;
	
		if($this->mailExists($param->email1))
			return self::UNAVAILABLE_EMAIL;
	
		if(strlen($param->motdepasse1) < 5 || $param->motdepasse1 != $param->motdepasse2)
			return self::INVALID_PASSWORD;
	
		$client = $param->asClient();
		if($client->telfixe == "" && $client->telport == "")
			return self::PHONE_REQUIRED;
		
		$client->lang = ActionsLang::instance()->get_id_langue_courante();
		
		$_SESSION['navig']->formcli = $client;
	
		ActionsModules::instance()->appel_module("avantclient");
	
		$_SESSION['navig']->client = $client;
	
		$client->crypter();
	
		$client->id = $client->add();
	
		$client->ref = date("ymdHi") . genid($client->id, 6);
		$client->maj();
	
		$_SESSION['navig']->client = $client;
		$_SESSION['navig']->connecte = 1;
			
		ActionsModules::instance()->appel_module("apresclient", $client);
		
		$mailer = new TheliaNewAccoutMailer($client, $param->motdepasse1);
		$proddebug = intval(Variable::lire("proddebug"));
		if($proddebug)
		{
			$filename = __DIR__ . "/mail.log";
			file_put_contents($filename, $mailer->Send());
		}
		else
			$mailer->Send();
		
		return self::SUCCESS;

	}
	
	protected function modifiercompte()
	{
		$properties = ClienProxy::getRequestedParameters();
		if( ! $this->validateRequestedParams($properties))
			return self::MISSING_PARAMETER;
		
		$param = new ClienProxy($properties);
		
		if(! $this->validateEmptyValues($param, $properties, array("telfixe", "telport")))
			return self::INVALID_PARAMETER;
			
		if($param->telfixe == "" && $param->telport == "")
			return self::PHONE_REQUIRED;
		
		$client = new Client($_SESSION['navig']->client->id);
		$param->updateClient($client);
		
		$_SESSION['navig']->formcli = $client;
	
		ActionsModules::instance()->appel_module("avantmodifcompte");
		$client->maj();
		$_SESSION['navig']->client = $client;
			
		ActionsModules::instance()->appel_module("apresmodifcompte", $client);
		
		return self::SUCCESS;
	}
	
	protected function modifierconnexion()
	{
		if( ! $this->validateRequestedParams(UpdateAccountParams::$params))
			return self::MISSING_PARAMETER;
		
		$param = new UpdateAccountParams();
		
		$pwdChanged = false;
		$emailError = false;
		$emailChanged = false;
		$currentEmail = $_SESSION['navig']->client->email;
		$currentPwd = $_SESSION['navig']->client->motdepasse;
		if($currentEmail != $param->email1)
		{
			$emailChanged = true;
			if($param->email1 == $param->email2)
			{
				if( ! $this->validateEmail($param->email1))
					return self::INVALID_EMAIL . " validateEmail";
				if( ! $this->mailAvailable($param->email1, $id))
					return self::UNAVAILABLE_EMAIL;
			}
			else
				return self::INVALID_PARAMETER;
		}
		
		$n = strlen($param->motdepasse2);
		if($n)
		{
			if($n < 6)
				return self::INVALID_PASSWORD;
			if($param->motdepasse1 != $param->motdepasse2)
				return self::INVALID_PARAMETER;
			
			$client = new Client();
			$client->motdepasse = $param->motdepasse1;
			$client->crypter();
			if($client->motdepasse != $currentPwd)
				$pwdChanged = true;
		}
		
		if( ! $pwdChanged && ! $emailChanged)
			return self::UNCHANGED;
		
		$client = new Client($this->clientId);
		
		ActionsModules::instance()->appel_module("avantmodifcompte");
		
		if($emailChanged)
		{
			$client->email = $param->email1;
		}
		if($pwdChanged)
		{
			$client->motdepasse = $param->motdepasse1;
			$client->crypter();
		}
		
		$client->maj();
		
		$_SESSION['navig']->client = $client;
		$_SESSION['navig']->formcli = $client;
		
		ActionsModules::instance()->appel_module("apresmodifcompte", $client);
		
		return self::SUCCESS;
	}
	private function getMissingParams($params)
	{
		$result = array();
		foreach ($params as $name)
		{
			if( ! isset($_POST[$name]))
			{
				$result[] = $name;
			}
		}
		return implode("\n", $result);
	}
	protected function creerlivraison()
	{
		$properties = AddressProxy::$params;
		if( ! $this->validateRequestedParams($properties))
			return self::MISSING_PARAMETER;// . " (all)\n" . $this->getMissingParams($properties);
		
		$proxy = new AddressProxy();
		
		if( ! $this->validateEmptyValues($proxy, AddressProxy::getRequestedParameters()))
			return self::INVALID_PARAMETER;// . " (empty)\n" . $this->getEmptyParams($proxy, $properties);
		
		$address = $proxy->asAdresse();
		$proxy = null;
		$address->client = $this->clientId;
		$address->id = $address->add();
		
		$_SESSION['navig']->adresse = $address->id;
		ActionsModules::instance()->appel_module("apres_creerlivraison", $address);
		
		return self::SUCCESS;
	}
	
	protected function modifierlivraison()
	{
		$properties = array_slice(AddressProxy::$params, 0);
		$properties[] = "id";
		if( ! $this->validateRequestedParams($properties))
		{
			return self::MISSING_PARAMETER;
		}
		$proxy = new AddressProxy();
		$address = $proxy->asAdresse($_POST["id"]);
		
		$_SESSION['navig']->formadr = $address;
		$address->maj();
		ActionsModules::instance()->appel_module("apres_modifierlivraison", $address);
		return self::SUCCESS;
	}
	
	protected function supprimerlivraison()
	{
		$properties = array("id");
		if( ! $this->validateRequestedParams($properties))
			return self::MISSING_PARAMETER;
		
		$id = (int) $_POST["id"];
		$address = new Adresse($id);
		$address->delete();
		
		if((int) $_SESSION['navig']->adresse == $id)
			$_SESSION['navig']->adresse = 0;
		
		return self::SUCCESS;
	}
	
	///////////////
	//   UTILS   //
	///////////////
	
	private function validateClientId()
	{
		return (isset($_POST["client_id"]) && (int) $_POST["client_id"] == $this->clientId);
	}
	
	private function validateEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	private function mailAvailable($email, $id)
	{
		$pdo = $this->getPdo();
		$query = "SELECT COUNT(*) FROM " . Client::TABLE . " WHERE email=? AND id!=?";
		$stmt = $pdo->prepare($query);
		$stmt->bindValue(1, $email, PDO::PARAM_STR);
		$stmt->bindValue(2, $id, PDO::PARAM_INT);
		return $this->getStatementCount($stmt);
	}
	
	private function mailExists($email)
	{
		$pdo = $this->getPdo();
		$query = "SELECT COUNT(*) FROM " . Client::TABLE . " WHERE email=?";
		$stmt = $pdo->prepare($query);
		$stmt->bindValue(1, $email, PDO::PARAM_STR);
		return $this->getStatementCount($stmt) == 0;
	}
	
	private function getStatementCount(PDOStatement $stmt)
	{
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_COLUMN, 0);
		$count = $stmt->fetch();
		return $count == 0;
	}
	
	private function validateRequestedParams($names)
	{
		$result = true;
		foreach ($names as $name) 
		{
			if( ! isset($_POST[$name]))
			{
				$result = false;
				break;
			}
		}
		return $result;
	}
	
	private function getEmptyParams($target, $properties, $except = null)
	{
		$result = array();
		if(! $except)
			$except = array();
		foreach ($properties as $prop)
		{
			if(array_search($prop, $except) !== false)
				continue;
			if($target->{$prop} == "")
			{
				$result[] = $prop;
			}
		}
		return implode("\n", $result);
	}
	private function validateEmptyValues($target, $properties, $except = null)
	{
		if(! $except)
			$except = array();
		foreach ($properties as $prop)
		{
			if(array_search($prop, $except) !== false)
				continue;
			if($target->{$prop} == "")
			{
				$target = null;
				break;
			}
		}
		return $target != null;
	}
	
	/**
	 * @var PDO
	 **/
	private static $pdo;
	/**
	 * @return PDOThelia
	 * */
	private function getPdo()
	{
		return PDOThelia::getInstance();
	}
	
	private function removeEOL($value)
	{
		$value = preg_replace("/\n*/", "", $value);
		$value = preg_replace("/\r*/", "", $value);
		return $value;
	}
}