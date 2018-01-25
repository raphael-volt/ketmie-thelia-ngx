<?php

class TheliaNewAccoutMailer extends TheliaMailerBase
{
	private $clearPassword;
	
	function __construct(Client $client, $clearPassword)
	{
		$this->clearPassword = $clearPassword;
		parent::__construct($client, "creation_client");
	}
	protected function replaceVariables()
	{
		$cryptedPassword = $client->motdepasse;
		$this->client->motdepasse = $this->clearPassword;
		parent::replaceVariables();
		$this->client->motdepasse = $cryptedPassword;
	}
}