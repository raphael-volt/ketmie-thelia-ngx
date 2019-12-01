<?php

class VODeclination
{

    // declidispdesc.id
    public $id;

    // declidispdesc.declidisp
    public $declidisp;

    public $titre;

    public $declinaison_titre;

    public $declinaison;
}

class DeclinationGroup
{

    public $titre;

    public $id;

    /**
     *
     * @var VODeclination[]
     */
    public $items = array();
}

class DecinaisonsService
{

    const QUERY_DECLINATION = '
SELECT declidispdesc.id, declidispdesc.declidisp, 
declidispdesc.titre,
declinaisondesc.titre as declinaison_titre,
declinaisondesc.declinaison
FROM declidispdesc
LEFT JOIN declidisp ON declidisp.id = declidispdesc.declidisp
LEFT JOIN declinaisondesc ON declinaisondesc.declinaison = declidisp.declinaison
WHERE 1  
ORDER BY declinaisondesc.declinaison ASC';

    static function getLiClass($i)
    {
        $l = array(
            'listbloc'
        );
        if (($i % 2) == 0)
            $l[] = "dark";
        return implode(" ", $l);
    }

    private $_pluginUrl;

    /**
     *
     * @var PDO
     */
    private $pdo;

    /**
     *
     * @var DeclinationGroup[]
     */
    private $groups;

    /**
     *
     * @var Decliprix[]
     */
    private $_decliprixList;

    function __construct(PDO $pdo, $pluginUrl)
    {
        $this->pdo = $pdo;
        $this->_pluginUrl = $pluginUrl;
    }
    
    function getPluginUrl() {
        
        return $this->_pluginUrl . "?nom=decliprix";
    }

    /**
     *
     * @var Decliprix[]
     */
    private $_decliprixMap;

    /**
     *
     * @return Decliprix[]
     */
    public function getDecliprixList()
    {
        if ($this->_decliprixList)
            return $this->_decliprixList;
        $pdo = $this->pdo;
        $stmt = $pdo->query("SELECT id FROM " . Decliprix::TABLE);
        $l = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        $result = array();
        foreach ($l as $id) {
            $dp = new Decliprix();
            $dp->charger($id);
            $result[] = $dp;
        }
        $this->_decliprixList = $result;
        return $result;
    }

    /**
     * 
     * @return Decliprix[]
     */
    public function getDecliprixMap()
    {
        if ($this->_decliprixMap)
            return $this->_decliprixMap;
        $dpList = $this->getDecliprixList();
        $result = array();
        foreach ($dpList as $dp) {
            $result[$dp->id_declidisp] = $dp;
        }
        $this->_decliprixMap = $result;
        return $result;
    }

    /**
     *
     * @var PDOStatement
     */
    private $_hasPriceStmt;

    public function declinationHasPrice($id_declination)
    {
        if (! $this->_hasPriceStmt) {
            $this->_hasPriceStmt = $this->pdo->prepare('
SELECT dp.id FROM ' . Decliprix::TABLE . ' as dp
LEFT JOIN ' . Declidisp::TABLE . ' as dd ON dp.id_declidisp = dd.id
WHERE dd.declinaison = ? LIMIT 1;
');
        }
        $stmt = $this->_hasPriceStmt;
        $stmt->bindParam(1, $id_declination);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     *
     * @return DeclinationGroup[]
     */
    public function createGroups()
    {
        if ($this->groups)
            return $this->groups;
        $pdo = $this->pdo;
        $stmt = $pdo->query(self::QUERY_DECLINATION);
        $rows = $stmt->fetchAll(PDO::FETCH_CLASS, VODeclination::class);
        $groups = array();
        $map = array();
        foreach ($rows as $vo) {
            $vo instanceof VODeclination;
            if (! array_key_exists($vo->declinaison, $map)) {
                $g = new DeclinationGroup();
                $g->titre = $vo->declinaison_titre;
                $g->id = $vo->declinaison;
                $map[$vo->declinaison] = $g;
                $groups[] = $g;
            }
            $g->items[] = $vo;
        }
        
        $this->groups = $groups;
        return $groups;
    }

    public function adminDeclinationRenderer()
    {
        $this->tpl = array();
        $groups = $this->createGroups();
        foreach ($groups as $g) {
            $this->_adminDeclinationRenderer($g, self::getLiClass(1));
        }
        return $this->implodeTpl();
    }
    
    private function _declinationEditor($action, $id_declinaison){
        $map = $this->getDecliprixMap();
        $groups = $this->createGroups();
        $l = array();
        $g = null;
        foreach ($groups as $g) {
            if($g->id == $id_declinaison)
                break;
            $g = null;
        }
        if($action == 'creer') {
          foreach ($g->items as $value) {
              $dp = new Decliprix();
              $dp->ref="";
              $dp->prix=0.00;
              $dp->id_declidisp = $value->declidisp;
              $l[] = $dp;
          }  
        }
        else {
            foreach ($g->items as $value) {
                $dp = new Decliprix();
                $dp->chargerDeclidisp($value->declidisp);
                $l[] = $dp;
            }  
        }
        $tpl = array();
        foreach ($l as $dp) {
            $dp->id;
            $tpl[] = <<<EOT
                <ul>
					<li style="width:50px;">ID : {$dp->id}</li>
					<li><input type="text" 
                            name="decliprix[{$dp->id}][ref]" 
                            value="$dp->ref" class="form_court" /></li>
					<li><input type="text" 
                            name="decliprix[{$dp->id}][prix]" 
                            value="$dp->prix" class="form_court" /></li>
					<li style="padding-left: 90px;">
				</ul>
EOT;
        }
        return implode(PHP_EOL, $tpl);
    }
    private function _decliprixEditors(){
        return <<<EOL

EOL;
        
    }
    
    public function adminDeclinationEditor($action, $id_declinaison)
    {
        $this->tpl = array();
        $url = $this->getPluginUrl();
        
        switch ($action) {
            case "supprimer":
                break;
            case "valider":
                break;
            case "creer":
            case "editer":
                $data = $this->_declinationEditor($action, $id_declinaison);
                break;
            case "editer_decliprix":
            case "creer_decliprix":
                
                break;
            default:
                $list = true;
                $a = null;
                break;
        }
        $submitLabel = trad('VALIDER_LES_MODIFICATIONS', 'admin');
        return <<<EOT
<form action="$url" method="post" id="form_modif">
<input type="hidden" name="action" id="decliprixaction" value="{$action}_decliprix" />
<input type="hidden" name="id_declinaison" value="$id_declinaison" />
<div class="entete_liste_config">
	<div class="fonction_valider"><input type="submit" value="$submitLabel"/></div>
</div>
    $data
</form>        
EOT;
    }
    
    private function implodeTpl() {
        $tpl = $this->tpl;
        $this->tpl = null;
        return implode(PHP_EOL, $tpl);
    }

    private function _adminDeclinationTriggerRenderer($editable, $id_declinaison)
    {
        $action = $editable ? "editer" : "creer";
        $url = $this->getPluginUrl() . "&id_declinaison={$id_declinaison}&action=$action";
        $label = $editable ? "éditer" : "créer";
        return <<<EOT
        <a href="$url">$label</a>';
EOT;
    }

    private function _adminDeclinationRenderer(DeclinationGroup $g, $class)
    {
        $editable = $this->declinationHasPrice($g->id);
        $trigger = $this->_adminDeclinationTriggerRenderer($editable, $g->id);
        $data = $this->_adminDeclinationValuesRenderer($g, $editable);
        $this->tpl[] = <<<EOT
<ul class="$class">
	<li><span>$g->titre</span>
        $trigger
		<table>
			<tr>
				<th>Titre</th>
				<th>Ref</th>
				<th>Prix</th>
				<th></th>
			</tr>
            $data
    	</table>
    </li>
</ul>
EOT;
    }

    private function _declinationRowView($titre, $ref, $prix, $class = null)
    {
        if (! $class)
            $class = self::getLiClass(0);
        
        return <<<EOT
<tr class="{$class}">
	<td>{$titre}</td>
	<td>{$ref}</td>
	<td>{$prix}</td>
    <td></td>
</tr>
EOT;
    }

    private $tpl;

    private function _adminDeclinationValuesRenderer(DeclinationGroup $g, $editable)
    {
        $tpl = array();
        $i = 0;
        foreach ($g->items as $item) {
            $dp = $this->_getDecliprix($item->declidisp);
            $tpl[] = $this->_declinationRowView($item->titre, $dp ? $dp->ref : "", $dp ? $dp->prix : "", self::getLiClass($i ++));
        }
        return implode(PHP_EOL, $tpl);
    }

    private function _getDecliprix($declidisp)
    {
        $map = $this->getDecliprixMap();
        if (array_key_exists($declidisp, $map))
            return $map[$declidisp];
        return null;
    }
}
