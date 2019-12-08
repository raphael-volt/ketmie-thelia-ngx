<?php

class Dpxpdo
{

    /**
     *
     * @var Dpxpdo
     */
    private static $_instance;

    /**
     *
     * @return Dpxpdo
     */
    public static function getInstance()
    {
        if (! self::$_instance)
            self::$_instance = new Dpxpdo();
        return self::$_instance;
    }

    private $pdo;

    private function __construct()
    {
        $this->pdo = StaticConnection::getPDO();
        DpxStmt::$pdo = $this->pdo;
    }

    function createTables()
    {
        return DpxStmt::createTables();
    }

    function createDpxMap(Dpx $dpx)
    {
        $declidisps = $this->getVODeclidisps($dpx->declinaison);
        $caracdisps = $this->getVOCaracdisps($dpx->caracteristique);
        $map = new DpxMap($dpx->caracteristique, $dpx->declinaison);
        $map->declidisps = $declidisps;
        $rows = array();
        foreach ($caracdisps as $c) {
            foreach ($declidisps as $d) {
                $vo = new VODPXDisp();
                $vo->caracdisp = $c->caracdisp;
                $vo->declidisp = $d->declidisp;
                $vo->prix = 0.00;
                $vo->ref = "";
                $vo->titre = $d->titre;
                $c->items[] = $vo;
            }
            $map->caracdisps[] = $c;
        }
        return $map;
    }

    /**
     *
     * @param Dpx $dpx
     * @return DpxMap
     */
    function getDpxMap(Dpx $dpx)
    {
        /**
         *
         * @var VODPXDisp $rows
         */
        $rows = $this->bindExec(DpxStmt::get(DpxStmt::DPX_MAP_ROWS), 1, $dpx->id)->fetchAll();
        $decliMap = array();
        $caracMap = array();
        $declidisps = $this->getVODeclidisps($dpx->declinaison);
        $caracdisps = $this->getVOCaracdisps($dpx->caracteristique);
        $map = new DpxMap($dpx->caracteristique, $dpx->declinaison);
        $map->declidisps = $declidisps;
        foreach ($caracdisps as $v) {
            $caracMap[$v->caracdisp] = $v;
            $map->caracdisps[] = $v;
        }
        foreach ($declidisps as $v) {
            $decliMap[$v->declidisp] = $v->titre;
        }
        foreach ($rows as $v) {
            $v->titre = $decliMap[$v->declidisp];
            $caracMap[$v->caracdisp]->items[] = $v;
        }
        return $map;
    }

    function deleteDisp($dpxID)
    {
        $this->bindExec(DpxStmt::get(DpxStmt::DPXDISP_DELETE), 1, $dpxID);
    }

    /**
     *
     * @param Dpx $dpx
     * @param mixed $dpxID
     * @return boolean
     */
    function selectDpx(Dpx $dpx, $dpxID)
    {
        $stmt = DpxStmt::get(DpxStmt::DPX_SELECT);
        $this->bindExec($stmt, 1, $dpxID);
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            foreach ($dpx->bddvars as $key) {
                $dpx->{$key} = $row->{$key};
            }
            return true;
        }
        return false;
    }

    function createDpxdisps(Dpx $dpx)
    {
        $values = $_POST['disp'];
        $pdo = $this->pdo;
        $pdo->beginTransaction();
        $stmt = DpxStmt::get(DpxStmt::DPXDISP_INSERT);
        $stmt->bindParam(1, $dpx->id);
        foreach ($values as $v) {
            $stmt->bindParam(2, $v["caracdisp"]);
            $stmt->bindParam(3, $v["declidisp"]);
            $stmt->bindParam(4, $v["prix"]);
            $stmt->bindParam(5, $v["ref"]);
            $stmt->execute();
        }
        $pdo->commit();
    }
    
    function updateDpxdisps(Dpx $dpx) {
        $values = $_POST['disp'];
        $pdo = $this->pdo;
        $pdo->beginTransaction();
        $stmt = DpxStmt::get(DpxStmt::DPXDISP_UPDATE);
        foreach ($values as $v) {
            $stmt->bindParam(1, $v["ref"]);
            $stmt->bindParam(2, $v["prix"]);
            $stmt->bindParam(3, $v["id"]);
            $stmt->execute();
        }
        $pdo->commit();
    }

    /**
     *
     * @param mixed $dpxId
     * @return VOCaracdisp[]
     */
    private function getVOCaracdisps($dpxId)
    {
        return $this->bindExec(DpxStmt::get(DpxStmt::DPX_CARACDISP), 1, $dpxId)->fetchAll();
    }

    /**
     *
     * @param mixed $dpxId
     * @return VODeclidisp[]
     */
    private function getVODeclidisps($dpxId)
    {
        return $this->bindExec(DpxStmt::get(DpxStmt::DPX_DECLIDISP), 1, $dpxId)->fetchAll();
    }

    /**
     *
     * @param PDOStatement $stmt
     * @param mixed $param
     * @param mixed $value
     * @return PDOStatement
     */
    private function bindExec(PDOStatement $stmt, $param, $value)
    {
        $stmt->bindParam($param, $value);
        $stmt->execute();
        return $stmt;
    }
}

