<?php

class RubDesc {
	/**
	 * @var RubDesc
	 */
	public static $current = null;
	
	const HOME = "home";
	const GRID = "grid";
	const CONTACT = "contact";
	
	public $children = array();
	public $id;
	/**
	 * @var RubDesc
	 */
	public $parent;
	public $pid;
	public $child_index;
	public $lien;
	public $label;
	public $on = false;
	public $content = false;
	
	public function toJson()
	{
	    $clone = new stdClass();
	    $clone->id = $this->id;
	    $clone->label = $this->label;
	    $clone->content = $this->content;
	    $clone->children = array();
	    foreach ($this->children as $c) {
	        $clone->children[] = $c->toJson();
	    }
	    
	    return $clone;
	}
	private $_isProduitList = null;
	private $_isLinkable = null;
	
	public function isProduitList()
	{
		if($this->_isProduitList !== null)
			return $this->_isProduitList;
		
		$this->_isProduitList = false;
		
		if($this->lien == self::HOME
			|| $this->lien == self::CONTACT
			|| $this->lien == self::GRID)
			$this->_isProduitList = false;
		else 
		{
			foreach ($this->children as $item)
			{
				if(is_a($item, ProdDesc::class))
				{
					$this->_isProduitList = true;
					break;
				}
			}
		}	
		return $this->_isProduitList;		
	}
	public function isLinkable()
	{
		if($this->_isLinkable !== null)
			return $this->_isLinkable;
		
		if($this->lien == self::HOME 
				|| $this->lien == self::CONTACT
				|| $this->lien == self::GRID)
			$this->_isLinkable = true;
		else 
		{
			if($this->content === true)
				$this->_isLinkable = true;
			else 
			{
				$this->_isLinkable = false;
				if(count($this->children))
				{
					foreach ($this->children as $item) 
					{
						if(is_a($item, ProdDesc::class))
						{
							$this->_isLinkable = true;
							break;
						}
					}
				}
			}
		}
		return $this->_isLinkable;
	}
	private $rubChildren;
	public function getRubChildren()
	{
		if($this->rubChildren !== null)
			return $this->rubChildren;
		$this->rubChildren = array();
		foreach ($this->children as $item) 
		{
			if(is_a($item, RubDesc::class))
				$this->rubChildren[] = $item;
		}
		return $this->rubChildren;
	}
	private $prodChildren;
	public function getProdChildren()
	{
		if($this->prodChildren !== null)
			return $this->rubChildren;
		$this->rubChildren = array();
		foreach ($this->children as $item) 
		{
			if(is_a($item, ProdDesc::class))
				$this->prodChildren[] = $item;
			
		}
		return $this->prodChildren;
	}
}