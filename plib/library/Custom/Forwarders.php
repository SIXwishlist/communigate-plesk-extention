<?php
/*
*************** 
* This is a helper class for getting the necessary info for forwarders
***************
*/
class Modules_Communigate_Custom_Forwarders
{
	
	public $forwarders;
	public $domain;

    function __construct($domain) 
    {
        $this->domain = $domain;
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $this->setForwarders($cli);
    }

    /*
    *************** 
    * Setter for the forwarders
    * Sets forwarders to the communigate forwarders
    * and accounts with redirect rules
    ***************
    */
    public function setForwarders($cli)
    {
        // Getting Forwarders for domain
        $forwarders = $cli->ListForwarders($this->domain);
        foreach ($forwarders as $forwarder) {
            $forwardTo = $cli->GetForwarder("$forwarder@$this->domain");
            $this->forwarders["$forwarder@$this->domain"] = "$forwardTo";
        }

        // Getting account which has the redirect rule
        $accounts = array_keys($cli->ListAccounts($this->domain));
        
        foreach ($accounts as $account) {
            $rules =$cli->GetAccountRules("$account@$this->domain");
            $searchRulesForReddirect = $this->recursive_array_search('#Redirect', $rules);
            
            if (isset($rules[$searchRulesForReddirect]) &&
                    $searchRulesForReddirect !== false &&
                    $rules[$searchRulesForReddirect][0] != 0) {
                $this->forwarders["$account@$this->domain"] = $rules[$searchRulesForReddirect][3][0][1];
            }
        }
    }

    /*
    *************** 
    * Getter for forwarders
    ***************
    */
    public function GetForwarders()
    {
        return $this->forwarders;
    }

    /*
    *************** 
    * Method to delete forwarder
    * if the forwarder is account with redirect rule
    * the rule is disabled
    ***************
    */
    public function deleteForwarder($forwarder)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        $accounts = $cli->ListAccounts("$domain");
        $accounts = array_keys($accounts);
        $account = str_replace("@$domain", "", "$forwarder");
        $rules = $cli->GetAccountRules($forwarder);
        $searchRulesForReddirect = self::recursive_array_search('#Redirect', $rules);
        
        // existing account disable the redirect rule, cuz cannot manipulate
        // redirect rule
        if (in_array($account, $accounts)) {            
            $destination = $rules[$searchRulesForReddirect][3][0][1];
            $addRedirectRule = sprintf("UPDATEACCOUNTMAILRULE %s".
                '(0,"#Redirect",(),(("Redirect to",%s),(Discard,"---")))',
                $forwarder, $destination
                );

            $cli->SendCommand($addRedirectRule);
        } else {
            $cli->DeleteForwarder($forwarder);
        }
    }

    /*
    *************** 
    * Method for adding Forwarders
    * if the addressToForward is exiting account
    * a redirect rule is added
    ***************
    */
    public function addForwarder($addressToForward, $destination)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        
        $accounts = $cli->ListAccounts("$domain");
        $accounts = array_keys($accounts);

        if (in_array(str_replace("@$domain", "", "$addressToForward"), $accounts)) {

            $addRedirectRule = sprintf("UPDATEACCOUNTMAILRULE %s".
                '(1,"#Redirect",(),(("Redirect to",%s),(Discard,"---")))',
                $addressToForward, $destination
                );

            $cli->SendCommand($addRedirectRule);
            // Add rule to the AddressToForward to redirect all mail to the destination
        } else {
            // Create a forwarder
            $cli->CreateForwarder($addressToForward,$destination);
        }
    }


    /*
    *************** 
    * A recursive array search to find the redirect rules of accounts
    ***************
    */
    public function recursive_array_search($needle,$haystack) 
    {
        foreach($haystack as $key=>$value) {
            $current_key=$key;
            if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

}