<?php
/*
*************** 
* This is a helper class for getting the necessary info for filters
* and also providing the basing CRUD functionality
***************
*/
class Modules_Communigate_Custom_Filters
{
	/**
	 * Method to get all accounts that belog to a domain
	 * @param  string $domain the name of the domain
	 * @return CArrayDataProvider      data provider with account
	 */
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

	/**
	 * Method to get all the filters that belong to a account
	 * @param  string $account account name with domain added
	 * @return CArrayDataProvider      data provider with filter names
	 */
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

	/**
	 * Method to create a new filter fo account
	 * @param  AA $post    the post variable returnt from the create form
	 * @param  string $account account name with domain added
	 */
	public function createFilter($post, $account)
	{
		foreach ($post as $key => $value) {
			if ($this->startsWith($key, 'dataFilter')) {
				$data[] = $value;
			} elseif ($this->startsWith($key, 'oprationFilter')) {
				$operations[] = $value;
			} elseif ($this->startsWith($key, 'parameterFilter')) {
				$parameters[] = $value; 
			} elseif ($this->startsWith($key, 'actionFilter')) {
				$actions[] = $value;
			} elseif ($this->startsWith($key, 'actionParameter')) {
				$parametersActions[] = $value;
			}
		}

		for ($i=0; $i < count($data); $i++) { 
			$ruleSettings[] = array($data[$i], $operations[$i], $parameters[$i]);
		}

		for ($i=0; $i < count($actions); $i++) { 
			$actionsForRules[] = array($actions[$i], $parametersActions[$i]);
		}

		$answer = array($post['priority'], $post['name'], $ruleSettings, $actionsForRules);
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

		$rules = $cli->GetAccountRules($account);
		array_push($rules, $answer);

		$cli->SetAccountRules($account, $rules);
	}

	/**
	 * Method for deleting a filter of account
	 * @param  string $filterName the name of the filter
	 * @param  string $account    the name of the account with domain added
	 * @return [type]             [description]
	 */
	public function deleteFilter($filterName, $account)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		$filters = $cli->GetAccountRules($account);
	
		foreach ($filters as $filter) {
			if ($filter[1] == $filterName) {
				$filterInfo = $filter;
			}
		}
		if(($key = array_search($filterInfo, $filters)) !== false) {
			unset($filters[$key]);
		}
		$cli->SetAccountRules($account, array_values($filters));
	}

    private function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

}