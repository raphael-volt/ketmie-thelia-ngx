<?php

class BO_CaracHelper
{
	public $groups = array();
	
	public function BO_CaracHelper($rows=null)
	{
		$id=-1;
		$metal=-1;
		if( ! $rows)
			return;
		
		foreach ($rows as $row)
		{
			if($id != $row->caracdisp || $metal != $row->metal)
			{
				$group = $this->addGroup($row);
				$metal = $group->metal;
				$id = $group->caracdisp;
			}
			$group->addItem($row);
		}
	}
	
	public function hasGroup($declidis, $metal)
	{
		$declidis = intval($declidis);
		if(array_key_exists($declidis, $this->groups))
		{
			if(array_key_exists($metal, $this->groups[$declidis]))
				return true;
		}
		return false;
	}
	/**
	 * @param unknown $row
	 * @return BO_CaracGroup
	 */
	public function addRowGroup($rows)
	{
		$group = $this->addGroup($rows[0]);
		foreach ($rows as $row)
		{
			$group->addItem($row);
		}
		return $group;
	}
	/**
	 * @param unknown $row
	 * @return BO_CaracGroup
	 */
	public function addGroup($row)
	{
		$group = new BO_CaracGroup($row);
		$this->groups[$group->caracdisp][$group->metal] = $group;
		return $group;
	}

	public function getGroup($declidisp, $metal = "zinc")
	{
		$declidisp = intval($declidisp);
		if(array_key_exists($declidisp, $this->groups))
		{
			if(array_key_exists($metal, $this->groups[$declidisp]))
				return $this->groups[$declidisp][$metal];
		}
		return null;
	}

	public function getGroupByCaracId($id)
	{
		$id = intval($id);
		$result = null;
		foreach ($this->groups as $metal=>$group)
		{
			$group instanceof BO_CaracGroup;
			if(array_key_exists($id, $group->caracs))
			{
				$result = $group;
				break;
			}
		}
		return $result;
	}
}
?>