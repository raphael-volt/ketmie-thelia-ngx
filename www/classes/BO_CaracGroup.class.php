<?php
class BO_CaracGroup
{
	public $caracdisp;
	public $titre;
	public $caracs;
	public $metal;
	function __construct($row)
	{
		if(! $row)
			return;

		$this->caracdisp = intval($row->caracdisp);
		$this->titre = $row->titre;
		$this->caracs = array();
		if(property_exists($row, "metal"))
			$this->metal = $row->metal;
	}
	function addItem($row)
	{
		$item = new BO_Carac($row);
		$this->caracs[$item->id] = $item;
	}
	public function getCaracById($id)
	{
		$id = intval($id);
		if(array_key_exists($id, $this->caracs))
			return $this->caracs[$id];
		return null;
	}
}
?>