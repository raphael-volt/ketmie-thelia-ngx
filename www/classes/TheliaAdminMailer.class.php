<?php

class TheliaAdminMailer extends TheliaDeliveryMailer
{
	/**
	 * @var Client
	 */
	protected $admin;
	
	function __construct(Commande $commande, $messageNom="mailconfirmadm")
	{
		$admin = new Client();
		$admin->email = Variable::lire("emailscommande");
		$admin->prenom = "Administrateur";
		$admin->nom = Variable::lire("nomsite");
		$this->admin = $admin;
		//die(Variable::lire("emailnoreply"));
		parent::__construct($commande, $messageNom);
		
	}
	
	public function AddAddress($address, $nom)
	{
		return parent::AddAddress($this->admin->email, $this->admin->nom);
	}
	protected function setupMail()
	{
		parent::setupMail();
		$this->From = Variable::lire("emailnoreply");
	}
} 