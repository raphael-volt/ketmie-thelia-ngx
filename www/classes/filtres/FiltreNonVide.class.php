<?php
require_once __DIR__ . "/../../fonctions/autoload.php";

class FiltreVide extends FiltreBase {

	public function __construct()
	{
		parent::__construct("`\#FILTRE_nonvide\(([^\|]*)\|\|([^\)]+)\)`");
	}

	public function calcule($match)
	{
		return ! trim($match[1]) == '' ? '' : $match[2];
	}
}