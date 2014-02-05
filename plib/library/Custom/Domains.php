<?php 

/**
* 
*/
class Modules_Communigate_Custom_Domains
{
	
	private $domain;

	public function ListDomains()
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		return $cli->ListDomains();
	}

	public function setDomain($name)
	{
		$this->domain = $name;
	}


	public function getDomain()
	{
		return $domain;
	}



}