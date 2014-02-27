<?php 
/**
* 			
*/
class Modules_Communigate_Custom_RemotePop
{
	
	/**
	 * Method for getting the data for the grid view
	 * @return CArrayDataProvider data for the grid view
	 */
	public function getDataForPops($account)
	{		
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		// $cli->setDebug(1);
		$pops = $cli->GetAccountRPOP($account);
		if (!empty($pops)) {
			$i = 0;
			foreach ($pops as $popName => $settings) {
				
				$data[$i] = (
					array('id'=> $i,
						'popName'=> $popName,
						'account' => $account,
						));
				$i ++;
			}
			return $data;
		} else {
			return array();
		}
	}

	/**
	 * Method for adding the a new remote pop to account
	 * @param  string $account account to add the rpop with domain
	 */
	public function addRemotePop($account, $details)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		$pops = $cli->GetAccountRPOP($account);
		$details = array_merge($pops, $details);
		$cli->SetAccountRPOP($account, $details);
	}

	/**
	 * Method for removing a pop from account
	 * @param  string $name    the name of the rpop
	 * @param  string $account name of account with domain added
	 */
	public function deleteRemotePop($name, $account)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		// $cli->setDebug(1);
		$pops = $cli->GetAccountRPOP($account);
		unset($pops[$name]);
		if (empty($pops)) {
			$cli->SendCommand("SETACCOUNTRPOPS $account {}");
		} else {
			$cli->SetAccountRPOP($account, $pops);
		}
	}

	/**
	 * Method for getting info for a pop
	 * @param  string $name    the name of the rpop
	 * @param  string $account name of account with domain added
	 */
	public function getPopInfo($name, $account)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		// $cli->setDebug(1);
		$pops = $cli->GetAccountRPOP($account);
		return $pops[$name];
	}
}
?>