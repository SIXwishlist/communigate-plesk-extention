<?php  
/*
*************** 
* Class for validating if an account exists used in mailing list form 
***************
*/
class Modules_Communigate_Validators_Account extends Zend_Validate_Abstract
{


    const EXISTS = 'exists';
 
    protected $_messageTemplates = array(
        self::EXISTS => "The account '%value%' doesn't exist!"
    );

     
    public function isValid($value)
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        // var_dump($value);

        if (strpos($value, "@$domain")) {
            $value = str_replace("@$domain", "", "$value");
            // $this->_setValue($value1);
        } 

        // var_dump(str_replace("@abc.bg", "", "ivan@abc.bg"));

        $this->_setValue($value);
 
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

        // Checking if the passed account exists in accounts
        $accounts = array_keys($cli->ListAccounts($domain));
        // var_dump($accounts, !in_array($value, $accounts) );
        if (!in_array($value, $accounts))  {
            $this->_error(self::EXISTS);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();


    }

}