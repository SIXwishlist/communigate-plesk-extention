<?php

class  Modules_Communigate_EventListener implements EventListener
{
	

	// public $accountName = "";
	// public $realName = "";

	public function handleEvent($objectType, $objectId, $action, $oldValues, $newValues)
	{

		pm_Context::init('communigate');

		// Logging to panel
		// error_log("action is " . $action . "\n");
		// error_log("New Values" . print_r($oldValues, true));
		// error_log("New Values" . print_r($newValues, true));
		// error_log("objectId is" . $objectId . "\n");
		// error_log("objectType is" . $objectType . "\n");


		// Handling the mail creation from user panel
		// To do: check if mail create or  forward create problems
		// with password setting when adding forwarding

		if ($action == "mailname_update") {
			if (substr($newValues["Password"], -2) !== "++") {
				$mailBoxName = $newValues['Mailname'];
				$mailBoxName = substr($mailBoxName, 0, strpos($mailBoxName, '@'));
				$password = $newValues['Password'];
				$domainName = $newValues['Domain Name'];
				$acc = new Modules_Communigate_Custom_Accounts($domainName);
				$acc->createAccount($mailBoxName, '', $mailBoxName, $password);

				}

		}
		
		// Handling the deletion of domain
		if ($action == "domain_delete") {
			
			$domainName = $oldValues['Domain Name'];
			
			$cli = Modules_Communigate_Custom_Accounts::ConnectToCG($domainName);
			$cli->DeleteDomain($domainName, true);

		}

		// Setting the class variables for the client 
		// if ($action == "client_create") {

		// 	$this->accountName = $newValues['Login Name'];
		// 	$this->realName = $newValues['Contact Name'];

		// }
		
		// Handling the domain creation
		// and client creation
		if ($action == "domain_create"){
			
			// create domain in communigate server
			
			$domainName = $newValues['Domain Name'];

			$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

			$cli->CreateDomain($domainName);

			// if ($this->accountName !== "" && $this->realName !== "") {
			// 	$acc = new Modules_Communigate_Custom_Accounts($domainName);
			// 	$acc->createAccount($this->accountName, 'Multi-Mailbox', 'Mail POP IMAP PWD WebMail WebSite',
			// 	$this->realName, '');

			// }
		}

	}
}
return new  Modules_Communigate_EventListener();

?>