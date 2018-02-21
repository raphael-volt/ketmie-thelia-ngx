<?php
require_once (dirname(realpath(__FILE__)) . '/../../../classes/filtres/FiltreCustomBase.class.php');

require_once 'client/plugins/filtreapi/CardController.class.php';
require_once 'client/plugins/filtreapi/Request.class.php';
require_once 'client/plugins/filtreapi/HTTPReponseHelper.class.php';
require_once 'client/plugins/filtreapi/ProductHelper.class.php';

class Filtreapi extends FiltreCustomBase
{

    /**
     *
     * @var ProductHelper
     */
    private $productHelper;

    private $createErrorMap;

    public function __construct()
    {
        parent::__construct("`\#FILTRE_api\(([^\|]+)\|\|([^\)]+)\)`");
        $this->productHelper = new ProductHelper();
        $this->createErrorMap = new stdClass();
    }

    private function product($id)
    {
        return $this->productHelper->getProduct($id, true);
    }

    private function descriptions($type, $id)
    {
        $result = new stdClass();
        $tn = null;
        switch ($type) {
            case "cms-content":
                $tn = "contenudesc";
                break;
            case "category":
                $tn = "rubriquedesc";
                break;
            
            case "product":
                $tn = "produitdesc";
                break;
            
            default:
                ;
                break;
        }
        if (! $tn) {
            $result->error = "Unable to find decription for type:'{$type}'";
            return $result;
        }
        $pdo = PDOThelia::getInstance();
        $stmt = $pdo->prepare("SELECT description FROM {$tn} WHERE id=?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $result->description = $stmt->fetchColumn(0);
        } else {
            $result->error = "Object of type:'{$type}' not found";
        }
        return $result;
    }

    private function arbo()
    {
        $arbo = new stdClass();
        $arbo->shopCategories = $this->catalog();
        $q = "SELECT c.id as id, c.classement as ci, cd.titre as label FROM contenu as c 
LEFT JOIN contenudesc as cd ON cd.id=c.id
ORDER BY c.classement";
        $pdo = PDOThelia::getInstance();
        $cms = $pdo->query($q);
        $arbo->cmsContents = $cms->fetchAll(PDO::FETCH_OBJ);
        return $arbo;
    }

    private function catalog()
    {
        $pdo = PDOThelia::getInstance();
        $query = "SELECT d.titre as label, d.description as content, r.id, r.parent as pid, r.lien, 
r.classement AS child_index FROM rubrique AS r
LEFT JOIN rubriquedesc AS d ON d.rubrique=r.id
ORDER BY parent, child_index";
        
        $result = $pdo->query($query);
        $result = $result->fetchAll(PDO::FETCH_CLASS, RubDesc::class);
        $query = "SELECT d.titre as label, p.id, p.ref, p.rubrique FROM " . Produit::TABLE . " as p 
LEFT JOIN " . Produitdesc::TABLE . " as d ON p.id=d.produit 
WHERE ligne=1 ORDER BY rubrique, classement";
        $produits = $pdo->query($query);
        $produits = $produits->fetchAll(PDO::FETCH_CLASS, ProdDesc::class);
        
        $prodDict = array();
        foreach ($produits as $prod) {
            $prod instanceof ProdDesc;
            $prodDict[$prod->rubrique][] = $prod;
            if ($prod->id == $this->currentProd)
                ProdDesc::$current = $prod;
        }
        $root = new RubDesc();
        $root->id = 0;
        $root->pid = 0;
        $rubs = array(
            0 => $root
        );
        
        foreach ($result as $r) {
            $rubs[$r->id] = $r;
            $r instanceof RubDesc;
            if (! empty($r->content) && strlen($r->content))
                $r->content = true;
            else
                $r->content = false;
        }
        foreach ($rubs as $id => $r) {
            $r instanceof RubDesc;
            if ($r == $root)
                continue;
            $p = $rubs[$r->pid];
            $p instanceof RubDesc;
            $r->parent = $p;
            $i = $r->child_index - 1;
            $p->children[$i] = $r;
        }
        foreach ($rubs as $id => $r) {
            $r instanceof RubDesc;
            if ($r == $root)
                continue;
            if (array_key_exists($r->id, $prodDict)) {
                $r->children = array_merge($r->children, $prodDict[$r->id]);
            }
        }
        return $root->toJson()->children;
    }

    private function getPostJson()
    {
        $fn = "php://input";
        $result = null;
        try {
            $result = json_decode(file_get_contents($fn));
        } catch (Exception $e) {}
        
        return $result;
    }

    private function logout()
    {
        ActionsModules::instance()->appel_module("avantdeconnexion", $_SESSION['navig']->client);
        
        $_SESSION['navig']->client = new Client();
        
        $_SESSION['navig']->connecte = 0;
        $_SESSION['navig']->adresse = 0;
        
        ActionsModules::instance()->appel_module("apresdeconnexion");
    }

    /**
     *
     * @return Client|NULL
     */
    private function getCurrentCustomerId()
    {
        $client = $this->getCurrentCustomer();
        if ($client)
            return $client->id;
        return null;
    }

    private function getCurrentCustomer()
    {
        if (isset($_SESSION["navig"]) && $_SESSION['navig']->connecte == 1 && property_exists($_SESSION["navig"], "client")) {
            return $_SESSION['navig']->client;
        }
        return null;
    }

    private function isConnected()
    {
        return (isset($_SESSION["navig"]) && $_SESSION['navig']->connecte == 1);
    }

    private function log($data)
    {
        file_put_contents(__DIR__ . "/log.txt", $data, FILE_APPEND);
    }

    private function logPrint($data)
    {
        $data = print_r($data, true);
        $this->log($data);
    }

    /**
     *
     * @param unknown $post
     * @return NULL|Client
     */
    private function login($post)
    {
        $client = new Client();
        if ($client->charger($post->email, $post->motdepasse)) {
            $_SESSION['navig']->client = $client;
            $_SESSION['navig']->connecte = 1;
            
            ActionsModules::instance()->appel_module("apresconnexion", $client);
        } else {
            return null;
        }
        unset($client->motdepasse);
        return $client;
    }

    private function getCustomerAddress()
    {
        $customer = $this->getCurrentCustomer();
        if (! $customer)
            return null;
        $id = $customer->id;
        $pdo = PDOThelia::getInstance();
        $q = "SELECT * FROM " . Adresse::TABLE . " WHERE client={$id}";
        $stmt = $pdo->query($q);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        return $stmt->fetchAll();
    }

    private function getEmailTaken($email)
    {
        $pdo = PDOThelia::getInstance();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM " . Client::TABLE . " WHERE email=?");
        $stmt->bindParam(1, $email);
        $stmt->execute();
        $res = (int) $stmt->fetchColumn(0);
        return $res == 1;
    }

    private function updateEmail($email)
    {
        $test = new Client();
        if ($test->existe($email))
            return false;
        $client = new Client();
        $client->charger_id($_SESSION['navig']->client->id);
        $client->email = $email;
        
        ActionsModules::instance()->appel_module("avantmodifcompte");
        
        $client->maj();
        $_SESSION['navig']->client = $client;
        
        ActionsModules::instance()->appel_module("apresmodifcompte", $client);
        
        return true;
    }

    private function updatePassword($pwd)
    {
        if (strlen($pwd) < 6)
            return false;
        $client = new Client($_SESSION['navig']->client->id);
        $client->motdepasse = strip_tags($pwd);
        $client->crypter();
        $client->maj();
        
        $_SESSION['navig']->client = $client;
        
        ActionsModules::instance()->appel_module("apresmodifcompte", $client);
        
        return true;
    }

    /**
     *
     * @param stdClass $json
     */
    private function updateCustomer($json)
    {
        $client = new Client();
        $client->charger_id($_SESSION['navig']->client->id);
        foreach ($json as $key => $value) {
            $client->{$key} = $value;
        }
        ActionsModules::instance()->appel_module("avantmodifcompte");
        $client->maj();
        $_SESSION['navig']->client = $client;
        ActionsModules::instance()->appel_module("apresmodifcompte", $client);
        return true;
    }

    /**
     *
     * @param stdClass $json
     */
    private function createCustomer($json)
    {
        $test = new Client();
        $valid = true;
        $props = array(
            "raison",
            "nom",
            "prenom",
            "email",
            "motdepasse",
            "adresse1",
            "cpostal",
            "ville",
            "pays"
        );
        $this->createErrorMap->missingProperties = array();
        foreach ($props as $prop) {
            if (! property_exists($json, $prop)) {
                $valid = false;
                $this->createErrorMap->missingProperties[] = $prop;
            }
        }
        if (! property_exists($json, "telfix") && ! property_exists($json, "telport")) {
            $this->createErrorMap->missingProperties[] = "telfix|telport";
            $valid = false;
        }
        if (! $valid)
            return null;
        if ($test->existe($json->email)) {
            $this->createErrorMap->emailExists = true;
            return null;
        }
        
        $client = new Client();
        foreach ($json as $key => $value) {
            $client->{$key} = $value;
        }
        $client->type = "0";
        $client->lang = ActionsLang::instance()->get_id_langue_courante();
        
        ActionsModules::instance()->appel_module("avantclient");
        
        $client->crypter();
        $client->id = $client->add();
        
        return $client;
    }

    private function createAddress($address)
    {
        $result = new Adresse();
        foreach ($address as $key => $value) {
            $result->{$key} = $value;
        }
        $result->client = $this->getCurrentCustomerId();
        if ($result->client == null)
            return null;
        $result->id = $result->add();
        return $result;
    }

    private function updateAddress($address)
    {
        if (! property_exists($address, "id"))
            return null;
        $id = $address->id;
        $result = new Adresse();
        $result->charger_id($id);
        foreach ($address as $key => $value) {
            $result->{$key} = $value;
        }
        $result->maj();
        return true;
    }

    private function deleteAddress($address)
    {
        if (! property_exists($address, "id"))
            return null;
        $id = $address->id;
        $result = new Adresse();
        $result->charger_id($id);
        $result->delete();
        return true;
    }

    private function getCountries()
    {
        $pdo = PDOThelia::getInstance();
        $stmt = "SELECT d.titre as name, p.id FROM " . Pays::TABLE . " as p
LEFT JOIN " . Paysdesc::TABLE . " as d on d.id=p.id
WHERE zone<=7
ORDER BY titre";
        $stmt = $pdo->query($stmt);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        return $stmt->fetchAll();
    }

    public function calcule($match)
    {
        $isPost = $_SERVER['REQUEST_METHOD'] == 'POST';
        $match = parent::calcule($match);
        $response = new Response(true);
        $action = $this->paramsUtil->action;
        $result = new stdClass();
        try {
            switch ($action) {
                case 'card':
                    {
                        $result = null;
                        $card = new CardController();
                        $card->setRequestParameters($this->paramsUtil->parameters, $this->getPostJson());
                        $response = $card->getResponse();
                        break;
                    }
                case 'countries':
                    {
                        $result = $this->getCountries();
                        break;
                    }
                case 'session':
                    {
                        
                        $result->session_id = session_id();
                        
                        break;
                    }
                case "catalog":
                    {
                        $result = $this->catalog();
                        break;
                    }
                case "arbo":
                    {
                        $result = $this->arbo();
                        break;
                    }
                case "descriptions":
                    {
                        $result = $this->descriptions($this->paramsUtil, $this->paramsUtil->parameters[1]);
                        break;
                    }
                
                case "product":
                    {
                        $result = $this->product($this->paramsUtil->parameters[0]);
                        break;
                    }
                case "customer":
                    {
                        $method = $this->paramsUtil->parameters[0];
                        
                        switch ($method) {
                            case "create":
                                {
                                    $response->success = false;
                                    
                                    $json = $this->getPostJson();
                                    
                                    if ($json == null) {
                                        $this->createErrorMap->error = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                        $this->createErrorMap->reason = "missing post data";
                                        $result = $this->createErrorMap;
                                        // $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                        break;
                                    }
                                    
                                    $json = $this->createCustomer($json);
                                    if ($json == null) {
                                        $this->createErrorMap->error = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                        $this->createErrorMap->reason = "createCustomer FAIL";
                                        $result = $this->createErrorMap;
                                        // $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                        break;
                                    }
                                    
                                    $result = $response->baseobj($json);
                                    unset($result->motdepasse);
                                    $response->success = true;
                                    break;
                                }
                            case "update":
                                {
                                    $response->success = false;
                                    if (! $this->isConnected()) {
                                        $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$UNAUTHORIZED);
                                        break;
                                    }
                                    
                                    $json = $this->getPostJson();
                                    
                                    if ($json == null) {
                                        $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                        break;
                                    }
                                    
                                    if (property_exists($json, "email")) {
                                        $response->success = $this->updateEmail($json->email);
                                    } else {
                                        if (property_exists($json, "password")) {
                                            $response->success = $this->updatePassword($json->password);
                                        } else {
                                            if (property_exists($json, "customer")) {
                                                $response->success = $this->updateCustomer($json->customer);
                                            }
                                        }
                                    }
                                    break;
                                }
                            case "emailTaken":
                                {
                                    $email = $this->paramsUtil->parameters[1];
                                    $result->taken = $this->getEmailTaken($email);
                                    break;
                                }
                            case "login":
                                {
                                    $json = $this->getPostJson();
                                    if ($json == null) {
                                        $json = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                        break;
                                    }
                                    $json = $this->login($json);
                                    if ($json) {
                                        $result = $response->baseobj($json);
                                        $response->success = true;
                                    } else {
                                        $response->success = false;
                                        $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$UNAUTHORIZED);
                                    }
                                    break;
                                }
                            case 'logout':
                                {
                                    $this->logout();
                                    $result = null;
                                    break;
                                }
                            case 'current':
                                {
                                    $result = $this->getCurrentCustomer();
                                    $response->success = $result != null;
                                    break;
                                }
                            case 'address':
                                {
                                    if ($isPost) {
                                        $response->success = false;
                                        if (! $this->isConnected()) {
                                            $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$UNAUTHORIZED);
                                            break;
                                        }
                                        $json = $this->getPostJson();
                                        if ($json == null) {
                                            $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                            break;
                                        }
                                        if (property_exists($json, "method")) {
                                            $method = $json->method;
                                            if (property_exists($json, "address")) {
                                                $address = $json->address;
                                                switch ($method) {
                                                    case "create":
                                                        {
                                                            $result = $this->createAddress($address);
                                                            break;
                                                        }
                                                    case "delete":
                                                        {
                                                            
                                                            $result = $this->deleteAddress($address);
                                                            break;
                                                        }
                                                    case "update":
                                                        {
                                                            
                                                            $result = $this->updateAddress($address);
                                                            break;
                                                        }
                                                    default:
                                                        {
                                                            $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                                            break;
                                                        }
                                                }
                                                if (! $result) {
                                                    $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                                } else {
                                                    $response->success = true;
                                                }
                                            } else {
                                                $result = HTTPReponseHelper::getErrorJson(HTTPReponseHelper::$BAD_REQUEST);
                                                break;
                                            }
                                        }
                                    } else {
                                        $result = $this->getCustomerAddress();
                                        $response->success = $this->isConnected();
                                    }
                                    break;
                                }
                            default:
                                ;
                                break;
                        }
                        break;
                    }
            }
        } catch (Exception $e) {
            $result = HTTPReponseHelper::getErrorJson(500, $e->getMessage());
            $response->success = false;
        }
        if ($result)
            $response->body = $result;
        
        return $response->serialize();
    }
}

