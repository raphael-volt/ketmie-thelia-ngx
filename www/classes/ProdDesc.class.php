<?php

class ProdDesc extends Produit
{
	/**
	 * @var ProdDesc
	 */
	public static $current=null;
	public $label;
	public $declinations = array();
	public $on=false;
	
	public function toJson() {
	    /*
	    return $this;
	    */
	    $clone = new stdClass();
	    $clone->label = $this->label;
	    $clone->id = $this->id;
	    $clone->ref = $this->ref;
	    $clone->price = $this->prix;
	    $clone->declinations = $this->declinations;
	    return $clone;
	}
}