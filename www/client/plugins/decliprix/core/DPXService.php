<?php
require_once __DIR__ . '/dpx.php';
require_once __DIR__ . '/PDXAdminRenderer.php';

class DPXService
{

    function __construct(Decliprix $dpx)
    {
        $this->dpx = $dpx;
        $this->pdo = StaticConnection::getPDO();
        
        $this->url_module = "module.php?nom=" . $this->getPluginNom();
    }

    /**
     * http://localhost:4400/admin_dev/module.php
     *
     * @var mixed
     */
    private $url_module;

    private $pdo;

    /**
     *
     * @var Decliprix
     */
    public $dpx;

    function getPluginNom()
    {
        return $this->dpx->getNom();
    }

    function getActionUrl($action, $id_declination = "")
    {
        if ($id_declination != "") {
            $id_declination = "&" . DPXAction::PARAM_ID_DECLINATION . "=$id_declination";
        }
        return $this->getPluginUrl() . "&action=$action" . $id_declination;
    }

    function getPluginUrl()
    {
        return $this->url_module;
    }

    function minPrice($id_declinaison) {
        $stmt = $this->pdo->prepare(DPXRow::PRICE_RANGE);
        $stmt->bindParam(1, $id_declinaison);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return $stmt->fetchColumn(0);
        }
        return false;
    }
    function maxPrice($id_declinaison) {
        $stmt = $this->pdo->prepare(DPXRow::PRICE_RANGE);
        $stmt->bindParam(1, $id_declinaison);
        $stmt->execute();
        if ($stmt->rowCount()) {
            return $stmt->fetchColumn(1);
        }
        return false;
    }
    
    function hasPdx($id_declinaison)
    {
        $stmt = $this->pdo->prepare(DPXRow::IS_DPX);
        $stmt->bindParam(1, $id_declinaison);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $num = $stmt->fetch(PDO::FETCH_COLUMN, 0);
            return $num > 0;
        }
        return false;
    }

    function getDpxByDeclination($id_declination)
    {
        $stmt = $this->pdo->prepare(DPXRow::SELECT_BY_DECLI);
        $stmt->bindParam(1, $id_declination);
        $stmt->execute();
        if (! $stmt->rowCount())
            return null;
        /**
         *
         * @var DPXRow[] $rows
         */
        $rows = $stmt->fetchAll(PDO::FETCH_CLASS, DPXRow::class);
        $row = $rows[0];
        $row instanceof DPXRow;
        $dpx = DPXRow::rowToDPX($row);
        foreach ($rows as $row) {
            $dpx->items[] = DPXRow::rowToDPXDesc($row);
        }
        return $dpx;
    }
    
    function getDecliprixByDeclination($id_declination)
    {
        $stmt = $this->pdo->prepare(DPXRow::SELECT_BY_DECLI);
        $stmt->bindParam(1, $id_declination);
        $stmt->execute();
        if (! $stmt->rowCount())
            return null;
            /**
             *
             * @var DPXRow[] $rows
             */
            $rows = $stmt->fetchAll(PDO::FETCH_CLASS, DPXRow::class);
            $row = $rows[0];
            $row instanceof DPXRow;
            $dpx = DPXRow::rowToDPX($row);
            foreach ($rows as $row) {
                $dpx->items[] = DPXRow::rowToDPXDesc($row);
            }
            return $dpx;
    }

    /**
     *
     * @return DPX[]
     */
    function getDPXCollection()
    {
        $stmt = $this->pdo->query(DPXRow::SELECT);
        return DPXRow::dpxCollection($stmt->fetchAll(PDO::FETCH_CLASS, DPXRow::class));
    }

    function activate($id)
    {
        $dpx = $this->getDpxByDeclination($id);
        foreach ($dpx->items as $i) {
            $d = new DecliPrix();
            $d->ref = $i->ref;
            $d->prix = $i->prix;
            $d->id_declidisp = $i->declidisp;
            $d->add();
        }
    }

    function declinaisonAction($id_declinaison, $action)
    {
        if(! $id_declinaison)
           return;
        switch ($action) {
            case DPXAction::ACTIVER:
                $this->activate($id_declinaison);
                break;
            case DPXAction::DESACTIVER:
                $this->desactiver($id_declinaison);
                break;
            case DPXAction::MAJ_DECLIPRIX:
                $this->inserer();
                break;
            case "ajouter":
                echo "<p>action={$action}</p>";
                return;
        }
        $renderer = new DPXAdminRenderer($this);
        $renderer->adminDeclinaison($id_declinaison);
    }

    function action($action = null, $id_declinaison = null)
    {
        if (! DPXAction::exists($action)) {
            $param = DPXAction::PARAM_ACTION;
            if (isset($_GET[$param])) {
                $action = $_GET[$param];
            } else {
                if (isset($_POST[$param])) {
                    $action = $_POST[$param];
                }
            }
        }
        if (! DPXAction::exists($action))
            $action = DPXAction::LIST;
        if (! $id_declinaison) {
            if (isset($_GET[DPXAction::PARAM_ID_DECLINATION]))
                $id_declinaison = $_GET[DPXAction::PARAM_ID_DECLINATION];
            elseif (isset($_POST[DPXAction::PARAM_ID_DECLINATION]))
                $id_declinaison = $_POST[DPXAction::PARAM_ID_DECLINATION];
        }
        
        $renderer = new DPXAdminRenderer($this);
        $list = true;
        switch ($action) {
            case DPXAction::ACTIVER:
                $this->activate($id_declinaison);
                $list = false;
                break;
            case DPXAction::DESACTIVER:
                $this->desactiver($id_declinaison);
                break;
            case DPXAction::MAJ_DECLIPRIX:
                $this->inserer();
                break;
            case DPXAction::EDITER:
                $list = false;
                $renderer->adminDecliprix($id_declinaison);
                break;
            default:
                ;
                break;
        }
        if (isset($_POST['admin_declinaison'])) {
            $renderer->adminEditer($id_declinaison);
        }
        if ($list) {
            $renderer->liste();
        }
    }

    function inserer()
    {
        $items = $_POST[$this->getPluginNom()];
        foreach ($items as $v) {
            // $v["prix"] = $v["prix"];
            $dpx = new DecliPrix();
            if (! $v["id"]) {
                $dpx->ref = $v["ref"];
                $dpx->prix = $v["prix"];
                $dpx->id_declidisp = $v["declidisp"];
                $dpx->add();
            } else {
                
                $dpx->charger($v["id"]);
                $dpx->ref = $v["ref"];
                $dpx->prix = floatval($v["prix"]);
                $dpx->maj();
            }
        }
    }

    function desactiver($id_declinaison)
    {
        $dpx = new Decliprix();
        $stmt = $this->pdo->prepare(DPXRow::SELECT_DESC);
        $stmt->bindParam(1, $id_declinaison);
        $stmt->execute();
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        
        foreach ($ids as $id) {
            $dpx->chargerDeclidisp($id);
            $dpx->delete();
        }
    }
}

