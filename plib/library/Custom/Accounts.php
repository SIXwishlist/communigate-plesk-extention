<?php
/*
*************** 
* This is a helper class for getting the necessary info for accounts
* and also providing the basing CRUD functionality for accounts
***************
*/
class Modules_Communigate_Custom_Accounts
{
	
	public $accounts;
	public $accountNames;
	public $emails;
	public $usage;
	public $accountAliases;
	public $domain;

    function __construct($domain) {
        $this->domain = $domain;
        $cli = $this->ConnectToCG();
        $this->setAccounts($cli);
        $this->setMails();
        $this->setUsage($cli);
        $this->setAliases($cli);
    }

    public function setAccounts($cli)
    {
        $accounts = $cli->ListAccounts($this->domain);
        $accounts = array_keys($accounts);
        $this->accountNames = $accounts;
        foreach ($accounts as $account) {
            $this->accounts[] = "$account" . "@". "$this->domain"; 
        }

    }
    public function setMails()
    {
        foreach ($this->accounts as $account) {
            $this->emails[] = "$account";
        }
    }

    public function setUsage($cli)
    {
        $fileInfo = array();
        foreach ($this->accounts as $account) {
            $info = $cli->GetStrorageFileInfo($account) ;
            $used = ltrim ($info[1],'#');
            $size = ltrim ($info[3],'#');
            $fileInfo[] = array('size' => $size,
                'used' => $used );
        }
        foreach ($fileInfo as $FI) {
            $this->usage[] = $this->percent($FI['used'], $FI['size']);
        }

    }

    public function setAliases($cli)
    {
        foreach ($this->accounts as $account) {
            $this->accountAliases[] = implode(", ",$cli->GetAccountAliases($account));
        }
    }

    public function ConnectToCG($host = '', $port = '', $login = '', $password = '')
    {

        $host = pm_Settings::get('host');
        $port = pm_Settings::get('port');
        $login = pm_Settings::get('userName');
        $password = pm_Settings::get('password');

        $cli = new Modules_Communigate_CLI_CLI();
    	// $cli->setDebug(1);
        $cli->Login($host,$port,$login,$password);
        return $cli;
    }

    private function percent($num_amount, $num_total) {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 0);
        return $count;
    }

    public function data($accountName = '')
    {
        $cli = self::ConnectToCG();
        $domain = $this->domain;
        
        for ($i=0; $i < count($this->accounts); $i++) { 
            $account = $this->accounts[$i];
            $settings = $cli->GetAccountSettings($account);
            $dataProvider[$i] = (
                array('id'=> $i,
                 'pagination'=>array(
                    'pageSize'=>5,),
                 'account'=> $this->accountNames[$i],
                 'E-mail'=> $this->emails[$i],
                 'type'=> $settings['ServiceClass'],
                 'usage' => $this->usage[$i],
                 'accountAliases' => $this->accountAliases[$i],
                 ));
            if ($account === $accountName && $accountName !== '') {
              $dataProvider = (
                 array(
                  'id'=> $i,
                  'pagination'=>array(
                     'pageSize'=>5,),
                  'account'=> $this->accounts[$i],
                  'E-mail'=> $this->emails[$i],
                  'type'=> $settings['ServiceClass'],
                  'usage' => $this->usage[$i],
                  'accountAliases' => $this->accountAliases[$i],
                  ));
              break;
          }
        }   
        return $dataProvider;
    }

	/*
	*************** 
	* Account CRUD
	***************
	*/
    // Access modes removed from function agruments
	public function createAccount($accountName, $serviceClass, $name, $accountPassword)
    {
        $cli = self::ConnectToCG();
        $domain = $this->domain;

        $UserData = array(
            "accountName" => "$accountName@$domain",
            "settings" => array(
                "ServiceClass" => "$serviceClass",
            // "AccessModes" => "$accessModes",
                "RealName" => "$name",
                "MaxAccountSize" => "100k"
                )
            );

        $cli->CreateAccount($UserData);

        if ($accountPassword !== "") {
            $cli->SetAccountPassword("$accountName@$domain",$accountPassword);
        }

        $cli->Logout();
    }

    public function getAccountData($account)
    {
        $cli = self::ConnectToCG();
        $domain = $this->domain;

        $info = array();

        $settings = $cli->GetAccountSettings("$account@$domain");

        if ($settings['settings'] !== NULL) {

            $info['accountName'] = $account;
            $info['name'] = $settings['settings']['RealName'];
            $info['accountType'] = $settings['settings']['accountType'];
                // if (is_string($settings['settings']['AccessModes'])) {
                //     $info['AccessModes'] = explode(", ", $settings['settings']['AccessModes']);
                // } else {
                // $info['AccessModesss'] = $settings['settings']['AccessModes'];
                // }
            return $info;
        } else {

            $info['accountName'] = $account;
            $info['name'] = $settings['RealName'];
            $info['accountType'] = $settings['ServiceClass'];
            // if (is_string($settings['AccessModes'])) {
            //     $info['AccessModes'] = explode(", ", $settings['AccessModes']);
            // } else {
            //     $info['AccessModesss'] = $settings['settings']['AccessModes'];
            // }
            return $info;
        }
    }


    // remove access modes and acc type add service classes
    public function updateAccount($accountName, $serviceClass, $name, $accountPassword, $newAccountName)   
    {
        $cli = self::ConnectToCG();
        $domain = $this->domain;

        $UserData = array(
            "ServiceClass" => "$serviceClass",
                // "AccessModes" => "$accessModes",
            "RealName" => "$name",
            "MaxAccountSize" => "100k"
            );

        $cli->SetAccountSettings("$accountName@$domain",$UserData);

        if ($accountPassword !== "") {
            $cli->SetAccountPassword("$accountName@$domain",$accountPassword);
        }

        $cli->RenameAccount("$accountName@$domain", "$newAccountName@$domain");
        $cli->Logout();
    }

    public function deleteAccount($account)
    {
        $cli = self::ConnectToCG();
        $domain = $this->domain;

        $cli->DeleteAccount("$account@$domain"); 
    }

    /*
    *************** 
    * Aliases
    ***************
    */
    public function createAlias($account, $alias)
    {
        $cli = self::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $aliases = $cli->GetAccountAliases("$account@$domain");

        array_push($aliases, $alias);

        $cli->SetAccountAliases("$account@$domain", $aliases);

        $cli->Logout();
    }

}