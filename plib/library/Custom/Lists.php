<?php 

/*
*************** 
* Helper class for getting info for mailing lists
* and also subscribing and unsubscribing from one
***************
*/
class Modules_Communigate_Custom_Lists
{
	
	public function GetMailingLists($domain)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $lists = $cli->ListLists($domain);

	    foreach ($lists as $list) {
            $info = $cli->GetList("$list@$domain");
            $subscribers = implode(", ", $cli->ListSubscribers("$list@$domain"));
            $mailingLists[$list] = array('owner' => $info['Owner'], 'subscribers' =>$subscribers, 'subscribtion mode'=> $info['mode']);
        }

        if ($mailingLists === null) {
        	return array('' => '');
        }
		return $mailingLists;
	}

	public function getListData($list)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
		return $cli->GetList("$list@$domain");
	}

	public function subscribe($account, $domain, $list)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		$cli->SendCommand("LIST $list@$domain subscribe $account@$domain");
	}

	public function unsubscribe($account, $domain, $list)
	{
		$cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
		
	}



}