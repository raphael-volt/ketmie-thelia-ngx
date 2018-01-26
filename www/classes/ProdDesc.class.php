<?php

class ProdDesc extends Produit
{
	/**
	 * @var ProdDesc
	 */
	public static $current=null;
	public $label;
	public $on=false;
	
	public function toJson() {
	    /*
	    return $this;
	    */
	    $clone = new stdClass();
	    $clone->label = $this->label;
	    $clone->id = $this->id;
	    
	    return $clone;
	}
}