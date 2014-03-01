<?php
/*
*************** 
* This is a helper class for getting the necessary info for filters
* and also providing the basing CRUD functionality
***************
*/
class Modules_Communigate_Custom_Filters
{
	public function getAllAccounts($domain)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $accounts = $cli->ListAccounts($domain);
        $accounts = array_keys($accounts);
        foreach ($accounts as $account) {
            $accountNames[] = "$account" . "@". "$domain"; 
        }

        return $accountNames;
	}

	public function getFilters($account)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		$filterNames = array();
		$filters = $cli->GetAccountRules($account);

		for ($i=0; $i < count($filters); $i++) { 
			$filterNames[] = $filters[$i][1];
		}
		return $filterNames;
	}


}