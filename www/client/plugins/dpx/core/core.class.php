<?php

class DpxStmt
{

    const DPX_SELECT = 1;

    const DPXDISP_DELETE = 2;

    const DPX_MAP_ROWS = 3;

    const DPX_DECLIDISP = 4;

    const DPX_CARACDISP = 5;

    const DPXDISP_INSERT = 6;

    const DPXDISP_UPDATE = 7;

    /**
     *
     * @var PDOStatement[]
     */
    private static $map = array();

    /**
     *
     * @var PDO
     */
    public static $pdo;

    private static function has($queryID)
    {
        return array_key_exists($queryID, self::$map);
    }

    private static function prepare($query, $fetchClass = null)
    {
        $stmt = self::$pdo->prepare($query);
        if ($fetchClass != null)
            $stmt->setFetchMode(PDO::FETCH_CLASS, $fetchClass);
        return $stmt;
    }

    /**
     *
     * @param int $queryID
     * @param PDO $pdo
     * @return PDOStatement
     */
    static function get($queryID)
    {
        $pdo = self::$pdo;
        if (! self::has($queryID)) {
            $caracdispdesc = Caracdispdesc::TABLE;
            $caracdisp = Caracdisp::TABLE;
            $dpxdisp = Dpxdisp::TABLE;
            $declidispdesc = Declidispdesc::TABLE;
            $declidisp = Declidisp::TABLE;
            $dpxdisp = Dpxdisp::TABLE;
            switch ($queryID) {
                case self::DPXDISP_DELETE:
                    {
                        self::$map[$queryID] = self::prepare("DELETE FROM " . Dpxdisp::TABLE . " WHERE dpx=?;");
                        break;
                    }
                case self::DPX_SELECT:
                    {
                        self::$map[$queryID] = self::prepare("SELECT * FROM " . Dpx::TABLE . " WHERE id=? LIMIT 1");
                        break;
                    }
                case self::DPX_MAP_ROWS:
                    {
                        self::$map[$queryID] = self::prepare("
SELECT {$dpxdisp}.* FROM {$dpxdisp}
LEFT JOIN {$declidispdesc} ON {$declidispdesc}.declidisp={$dpxdisp}.declidisp
LEFT JOIN {$caracdispdesc} ON {$caracdispdesc}.caracdisp={$dpxdisp}.caracdisp
WHERE {$dpxdisp}.dpx=?
ORDER BY {$caracdispdesc}.classement, {$declidispdesc}.classement;", VODPXDisp::class);
                        
                        break;
                    }
                case self::DPX_CARACDISP:
                    {
                        $q = "
SELECT {$caracdispdesc}.titre, {$caracdispdesc}.caracdisp
FROM {$caracdispdesc}
LEFT JOIN {$caracdisp} ON {$caracdisp}.id={$caracdispdesc}.caracdisp
WHERE {$caracdisp}.caracteristique=?
ORDER BY {$caracdispdesc}.classement;";
                        self::$map[$queryID] = self::prepare($q, VOCaracdisp::class);
                        break;
                    }
                case self::DPX_DECLIDISP:
                    {
                        $q = "
SELECT {$declidispdesc}.declidisp, {$declidispdesc}.titre
FROM {$declidispdesc}
LEFT JOIN {$declidisp} ON {$declidisp}.id={$declidispdesc}.declidisp
WHERE {$declidisp}.declinaison=?
ORDER BY {$declidispdesc}.classement;";
                        self::$map[$queryID] = self::prepare($q, VODeclidisp::class);
                        
                        break;
                    }
                case self::DPXDISP_INSERT:
                    {
                        $q="INSERT INTO $dpxdisp (dpx, caracdisp, declidisp, prix, ref) VALUES (?,?,?,?,?)";
                        self::$map[$queryID] = self::prepare($q);
                        
                        break;
                    }
                case self::DPXDISP_UPDATE:
                    {
                        self::$map[$queryID] = self::prepare("
UPDATE $dpxdisp SET ref = ?, prix = ? WHERE id = ?");
                        
                        break;
                    }
                default:
                    {
                        
                        ;
                        break;
                    }
            }
        }
        return self::$map[$queryID];
    }

    static function createTables()
    {
        return self::$pdo->query(self::createTablesQuery());
    }

    private static function createTablesQuery()
    {
        $dpx = Dpx::TABLE;
        $disp = Dpxdisp::TABLE;
        return "DROP TABLE IF EXISTS $dpx;
CREATE TABLE $dpx (
  id int(11) NOT NULL AUTO_INCREMENT,
  declinaison int(11) NOT NULL,
  caracteristique int(11) NOT NULL,
  titre tinytext NOT NULL,
  PRIMARY KEY (id)
);

DROP TABLE IF EXISTS $disp;
CREATE TABLE $disp (
  id int(11) NOT NULL AUTO_INCREMENT,
  dpx int(11) NOT NULL,
  caracdisp int(11) NOT NULL,
  declidisp int(11) NOT NULL,
  prix decimal(5,2) NOT NULL DEFAULT '0.00',
  ref tinytext NOT NULL DEFAULT '',
  PRIMARY KEY (id)
);
";
    }
}

class DpxMap
{

    function __construct($caracteristique = null, $declinaison = null)
    {
        $this->caracteristique = $caracteristique;
        $this->declinaison = $declinaison;
    }

    public $caracteristique;

    public $declinaison;

    /**
     *
     * @var VODeclidisp[]
     */
    public $declidisps = array();

    /**
     *
     * @var VOCaracdisp[]
     */
    public $caracdisps = array();
}

class VOMapItem
{

    public $titre;
}

class VOCaracdisp extends VOMapItem
{

    public $caracdisp;

    /**
     *
     * @var VODPXDisp[]
     */
    public $items = array();
}

class VODeclidisp extends VOMapItem
{

    public $declidisp;
}

class VODPXDisp extends Dpxdisp
{

    public $titre;
}

class TPLH
{
    
    const LISTER = "lister";
    
    const CREER = "creer";
    
    const CREER_DPX = "creerdpx";
    
    const CREER_DPX_DISPS = "creerdpxdisps";
    
    const MODIFIER_DPX = "modifierdpx";
    
    const EDITER_DPX = "editerdpx";
    
    const SUPPRIMER_DPX = "supprimerdpx";
    
    /**
     *
     * @var PDO
     */
    private $pdo;
    
    public $formId = "dpxform";
    
    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    function submitLink($label, $formId = "")
    {
        if ($formId == "")
            $formId = $this->formId;
            ?>
<a href="#"
	onclick="document.getElementById('<? echo $formId; ?>').submit()"><? echo $label; ?></a>
<?
    }

    function caracteristiqueSelectOption($current = "")
    {
        $pdo = $this->pdo;
        $carac = Caracteristique::TABLE;
        $desc = Caracteristiquedesc::TABLE;
        
        $q = <<<EOT
SELECT caracteristique, titre FROM $desc
JOIN $carac ON {$carac}.id = {$desc}.caracteristique
WHERE 1
ORDER BY {$carac}.classement;
EOT;
        $res = $pdo->query($q);
        $res = $res->fetchAll(PDO::FETCH_OBJ);
        if ($current == "") {
            echo <<<EOL
<option value="" selected>Selectionnez</option>
EOL;
        }
        foreach ($res as $v) {
            $v->selected = $current == $v->caracteristique ? ' selected' : '';
            echo <<<EOT
 <option value="{$v->caracteristique}"{$v->selected}>$v->titre</option>
EOT;
        }
    }

    function declinaisonSelectOptions($current = "")
    {
        $pdo = $this->pdo;
        $declinaisondesc = Declinaisondesc::TABLE;
        $declinaison = Declinaison::TABLE;
        $q = "SELECT declinaison, titre
FROM $declinaisondesc
LEFT JOIN $declinaison ON {$declinaison}.id={$declinaisondesc}.declinaison
ORDER BY {$declinaison}.classement;";
        
        $res = $pdo->query($q);
        $res = $res->fetchAll(PDO::FETCH_OBJ);
        if ($current == "") {
            echo <<<EOL
<option value="" selected>Selectionnez</option>
EOL;
        }
        foreach ($res as $v) {
            $v->selected = $current == $v->declinaison ? ' selected' : '';
            echo <<<EOT
 <option value="{$v->declinaison}"{$v->selected}>$v->titre</option>
EOT;
        }
    }

    function input($name, $value, $type = "hidden", $class = "", $formId = "")
    {
        if ($class != "")
            $class = "class=\"$class\" ";
        if ($formId == "")
            $formId = $this->formId;
        echo <<<EOT
<input {$class}form="$formId" name="$name" value="$value" type="$type">
EOT;
    }
}
?>