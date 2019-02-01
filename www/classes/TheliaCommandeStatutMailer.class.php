<?php

class TheliaCommandeStatutMailer extends TheliaMailerBase
{
	/**
	 * @var Commande
	 */
	private $commande;
	function __construct(Commande $commande)
	{
		$this->commande = $commande;
		parent::__construct(new Client($commande->client), "statut_commande");
		$this->_mailSubject = str_replace("__COMMANDE__", $commande->ref, $this->_mailSubject);	
	}
	
	protected function replaceVariables()
	{
		$commande = $this->commande;
		$statut = "";
		switch ($commande->statut) 
		{
			case Commande::ANNULE:
			{
				$statut = "Votre commande a été annulée.";
				break;
			}
			case Commande::PAYE:
			{
				$statut = "Le paiement de votre commande a été validé.";
				break;
			}
			case Commande::TRAITEMENT:
			{
				$statut = "Votre commande est en cours de préparation.";
				break;
			}
			case Commande::EXPEDIE:
			{
				$statut = "Votre commande a été expédiée.";
				break;
			}
			
			default:
				;
			break;
		}
		$this->messageDesc->description = str_replace("__STATUT__", $statut, $this->messageDesc->description);
		$this->messageDesc->descriptiontext = str_replace("__STATUT__", $statut, $this->messageDesc->descriptiontext);
		parent::replaceVariables();
		
		
	}
}