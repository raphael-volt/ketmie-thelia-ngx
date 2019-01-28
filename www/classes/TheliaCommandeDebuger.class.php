<?php

class TheliaCommandeDebuger
{
	
	public function resetSession()
	{
		// deconnexion();
		$_SESSION['navig'] = new Navigation();
	}
	
	public function setupTest()
	{
		
	}
}