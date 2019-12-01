<?php
require_once (dirname(realpath(__FILE__)) . '/../../../classes/filtres/FiltreBase.class.php');

class Filtredpx extends FiltreCustomBase
{

    /**
     *
     * @var DPXService
     */
    private $service;

    public function __construct()
    {
        parent::__construct("`\#FILTRE_dpx\(([^\|]+)\|\|([^\)]+)\)`");
        $this->service = new DPXService(new Decliprix());
    }

    public function calcule($match)
    {
        $match = parent::calcule($match);
        $params = $this->paramsUtil;
        
        switch ($params->action) {
            case "declioption":
                return $this->declioption($this->paramsUtil->parameters[0], $this->paramsUtil->parameters[1]);
                break;
            case "nom":
                return Filtredpx::class;
                break;
            case "isdpx":
                {
                    if ($params->getNumParameters() > 0) {
                        return $this->service->hasPdx($params->parameters[0]) ? "1" : "";
                    }
                    break;
                }
            case "min":
                {
                    if ($params->getNumParameters() > 0) {
                        $val = $this->service->minPrice($params->parameters[0]);
                        return $val ? $val : "";
                    }
                    break;
                }
            case "max":
                {
                    if ($params->getNumParameters() > 0) {
                        $val = $this->service->maxPrice($params->parameters[0]);
                        return $val ? $val : "";
                    }
                    break;
                }
            
            default:
                ;
                break;
        }
        return '';
    }

    function prerequis()
    {
        $liste = ActionsAdminModules::instance()->lister(3, 1, "decliprix");
        if (count($liste)) {
            $module = $liste[0];
            return $module->actif == 1;
        }
        return false;
    }

    private function declioption($index, $declinaison)
    {
        $index = floatval($index);
        $panier = $_SESSION['navig']->panier;
        $panier instanceof Panier;
        $article = $panier->tabarticle[$index];
        $article instanceof Article;
        $result = array();
        foreach ($article->perso as $perso) {
            if ($perso->declinaison == $declinaison) {
                break;
            }
            $perso = null;
        }
        if (! $perso)
            return "";
        
        $perso instanceof Perso;
        if ($this->service->hasPdx($perso->declinaison)) {
            $dpx = $this->service->getDecliprixByDeclination($perso->declinaison);
            foreach ($dpx->items as $i) {
                $selected = $i->declidisp == $perso->valeur ? "selected=\"selected\"" : "";
                $result[] = <<<EOT
<option value="{$i->declidisp}" {$selected}>$i->titre | $i->prix</option>
EOT;
            }
        } else {
            $dd = Declidisp::TABLE;
            $ddd = Declidispdesc::TABLE;
            // Les declidispdesc manquants sont placés en fin de classement
            $query = "
	                	select
                            ddd.titre,
	                		dd.*, IFNULL(ddd.classement," . PHP_INT_MAX . ") as classmt
	                	from $dd dd
	                	left join
	                		$ddd ddd on ddd.declidisp = dd.id
	                	where
	                		dd.declinaison='$perso->declinaison'
	                	order by
	                		classmt, dd.id";
            
            $stmt = $this->service->dpx->query($query);
            while ($row = $stmt->fetchObject()) {
                $selected = $row->id == $perso->valeur ? "selected=\"selected\"" : "";
                $result[] = <<<EOT
<option value="{$row->id}" $selected>$row->titre</option>
EOT;
                ;
            }
        }
        
        return implode(PHP_EOL, $result);
    }
}




















