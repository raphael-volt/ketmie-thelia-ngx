<?php

class DPXDesc
{

    public $id;

    public $declidisp;

    public $declinaison;

    public $ref;

    public $prix;

    public $titre;
}

class DPX
{

    public $titre;

    public $declinaison;

    public $active = false;

    /**
     *
     * @var DPXDesc
     */
    public $items;
}

class DPXRow
{

    public $declidisp;

    public $declidispTitre;

    public $declinaisonTitre;

    public $declinaison;

    public $dpxId;

    public $dpxRef;

    public $dpxPrix;

    const SELECT = "
SELECT declidispdesc.declidisp as declidisp,
declidispdesc.titre as declidispTitre,
declinaisondesc.titre as declinaisonTitre,
declinaisondesc.declinaison,
decliprix.id as dpxId, decliprix.ref as dpxRef, decliprix.prix as dpxPrix
FROM declidispdesc
LEFT JOIN declidisp ON declidisp.id = declidispdesc.declidisp
LEFT JOIN declinaisondesc ON declinaisondesc.declinaison = declidisp.declinaison
LEFT JOIN declinaison ON declinaison.id = declinaisondesc.declinaison
LEFT JOIN decliprix ON decliprix.id_declidisp = declidisp.id
WHERE 1
ORDER BY declinaison.classement, declinaisondesc.declinaison, declidispdesc.classement;";

    const SELECT_BY_DECLI = "
SELECT declidispdesc.declidisp as declidisp,
declidispdesc.titre as declidispTitre,
declinaisondesc.titre as declinaisonTitre,
declinaisondesc.declinaison,
decliprix.id as dpxId, decliprix.ref as dpxRef, decliprix.prix as dpxPrix
FROM declidispdesc
LEFT JOIN declidisp ON declidisp.id = declidispdesc.declidisp
LEFT JOIN declinaisondesc ON declinaisondesc.declinaison = declidisp.declinaison
LEFT JOIN declinaison ON declinaison.id = declinaisondesc.declinaison
LEFT JOIN decliprix ON decliprix.id_declidisp = declidisp.id
WHERE declinaisondesc.declinaison = ?
ORDER BY declidispdesc.classement";

    const IS_DPX = "
SELECT COUNT(declidisp.id) as num FROM declidisp
LEFT JOIN decliprix ON declidisp.id = decliprix.id_declidisp AND declidisp.declinaison = ?
WHERE decliprix.id IS NOT NULL LIMIT 1;";
    
    const DPX_BY_DECLINATION = "
SELECT declidispdesc.titre,
decliprix.prix, decliprix.ref
FROM declidispdesc
LEFT JOIN declidisp ON declidisp.id = declidispdesc.declidisp
LEFT JOIN decliprix ON declidispdesc.declidisp = decliprix.id_declidisp
WHERE decliprix.id IS NOT NULL AND declidisp.declinaison=?";
    
    
    const SELECT_DESC = "SELECT id FROM " . Declidisp::TABLE . " WHERE declinaison = ?";

    const PRICE_RANGE = "SELECT MIN(prix) as 'min', MAX(prix) as 'max'           
FROM decliprix
LEFT JOIN declidisp ON declidisp.id = decliprix.id_declidisp
WHERE declidisp.declinaison=?;";
    /**
     *
     * @param DPXRow[] $rows
     * @return DPX[]
     */
    static function dpxCollection($rows)
    {
        $map = array();
        $result = array();
        foreach ($rows as $row) {
            if (! array_key_exists($row->declinaison, $map)) {
                $map[$row->declinaison] = 0;
                $dpx = self::rowToDPX($row);
                $result[] = $dpx;
            }
            $dpx->items[] = self::rowToDPXDesc($row);
        }
        return $result;
    }

    static function rowToDPX(DPXRow $row)
    {
        $dpx = new DPX();
        $dpx->titre = $row->declinaisonTitre;
        $dpx->declinaison = $row->declinaison;
        $dpx->active = $row->dpxId != null;
        $dpx->items = array();
        return $dpx;
    }

    static function rowToDPXDesc(DPXRow $row)
    {
        $desc = new DPXDesc();
        $desc->declidisp = $row->declidisp;
        $desc->titre = $row->declidispTitre;
        $desc->declinaison = $row->declinaison;
        $desc->id = $row->dpxId;
        if ($desc->id == null) {
            $row->dpxPrix = 0.00;
            $row->dpxRef = "-";
        }
        $desc->prix = $row->dpxPrix;
        $desc->ref = $row->dpxRef;
        return $desc;
    }

    /**
     *
     * @param DPX[] $collection
     * @param mixed $id_declination
     */
    static function getDPX($collection, $id_declination)
    {
        foreach ($collection as $v) {
            if ($v->declinaison == $id_declination)
                break;
            $v = null;
        }
        return $v;
    }
}

class DPXAction
{

    const ACTIVER = "activerdpx";
    
    const DESACTIVER = "desactiverdpx";
    
    const EDITER = "editer";

    const MAJ_DECLIPRIX = "majdecliprix";

    const LIST = "liste";

    const PARAM_ID_DECLINATION = "id_declinaison";

    const PARAM_ACTION = "action";
    
    const FORM_ID = "form_modifdecliprix";

    static function exists($action)
    {
        $exists = true;
        switch ($action) {
            case self::ACTIVER:
            case self::DESACTIVER:
            case self::EDITER:
            case self::MAJ_DECLIPRIX:
            case self::LIST:
                break;
            
            default:
                $exists = false;
                break;
        }
        return $exists;
    }
}
?>