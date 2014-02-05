<?php  
/*
*************** 
* Class For validating the form for creating accounts and aliases
***************
*/
class Modules_Communigate_Validators_UniqueAA extends Zend_Validate_Abstract
{
    const UNIQUE = 'unique';
    
    protected $_messageTemplates = array(
        self::UNIQUE => "Account, Alias or Forwader '%value%' already exists!"
        );
    
    public function isValid($value)
    {
        $this->_setValue($value);

        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        $accounts = $cli->ListAccounts("$domain");

        $accounts = array_keys($accounts);
        
        // Проверка ако акаунта се ъпдейтва да не дава грешка когато името е същото
        $account = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'account', null );
        $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        if (!is_null($account) && $action === "update-Account") {
            if (($key = array_search($account, $accounts)) !== false) {
                unset($accounts[$key]);
            }
        }
        
        // Generating array with all aliaes
        foreach ($accounts as $account) {
            $aliases1[] = $cli->GetAccountAliases("$account@$domain");
        }
        foreach ($aliases1 as $alias) {
            foreach ($alias as $a) {
                $aliases[] = $a;
            }
        }

        $forwarders = $cli->ListForwarders($domain);

        if (in_array($value, $accounts) || in_array($value, $aliases) || in_array($this->value, $forwarders)) {
            $this->_error(self::UNIQUE);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();


    }
}