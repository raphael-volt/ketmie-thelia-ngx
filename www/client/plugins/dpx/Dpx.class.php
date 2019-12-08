<?php
Autoload::getInstance()->addDirectory(__DIR__ . "/core/");
require_once __DIR__ . '/core/core.class.php';
class Dpx extends PluginsClassiques
{

    const TABLE = 'dpx';
    
    public $id;
    
    public $declinaison;

    public $caracteristique;

    public $titre;

    public $table = self::TABLE;

    public $bddvars = array(
        "id",
        "declinaison",
        "caracteristique",
        "titre"
    );

    public $dpxPDO;
    public function __construct($id="")
    {
        parent::__construct("dpx");
        $this->dpxPDO = Dpxpdo::getInstance();
    }

    function init()
    {
        $this->dpxPDO->createTables();
    }
    
    function delete() {
        $this->dpxPDO->deleteDisp($this->id);
        parent::delete();
    }
    
    function charger_id($id){
        return $this->dpxPDO->selectDpx($this, $id);
    }
}