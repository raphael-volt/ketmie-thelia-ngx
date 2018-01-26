<?php
class BO_Carac
{
	public $size;
	public $price;
	public $id;
	public $metal;

	function __construct($row=null)
	{
		if( ! $row)
			return;
		$this->id = intval($row->id);
		$this->size = $row->size;
		$this->price = $row->price;
		if(property_exists($row, "metal"))
			$this->metal = $row->metal;
	}
}
?>