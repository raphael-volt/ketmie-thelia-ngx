<?php 
require_once __DIR__ . "/../../fonctions/autoload.php";

class ParametersUtil
{
	private static function removeEOL($value)
	{
		$value = preg_replace("/\r*/", "", $value);
		$value = preg_replace("/\n+/", "\n", $value);
		$value = preg_replace("/^\n+/", "", $value);
		$value = preg_replace("/\n+$/", "", $value);
		
		return $value;
	}
	
	public $action;
	public $parameters;
	/**
	 * @return number
	 */
	public function getNumParameters()
	{
		return count($this->parameters);
	}
	
	function __construct(array $parameters)
	{
		$n = count($parameters);
		if($n < 1)
			return;
		$this->action = self::removeEOL($parameters[1]);
		$this->parameters = array();
		if($n < 2)
			return;
		
		$parameters = explode("||", $parameters[2]);
		$n = count($parameters);
		for($i=0; $i<$n; $i++)
		{
			$this->parameters[] = self::removeEOL($parameters[$i]);
		}
	}
}
class FiltreCustomBase extends FiltreBase {

	/**
	 * @var ParametersUtil
	 */
	protected $paramsUtil;
	
	public function __construct($regexp)
	{
		
		parent::__construct($regexp);
		//parent::__construct("`\#FILTRE_custombase\(([^\|]*)\|\|([^\|]*)\|\|([^\)]*)\)`");
	}
	
	public function calcule($match)
	{
		$this->paramsUtil = new ParametersUtil($match);
		return $this->paramsUtil->parameters;
	}
	
}
?>