<?php  
/*
*************** 
* Class for validating if an account exists used in mailing list form 
***************
*/
class Modules_Communigate_Validators_UniqueServiceClass extends Zend_Validate_Abstract
{


    const EXISTS = 'exists';

    protected $_messageTemplates = array(
        self::EXISTS => "The Service Class '%value%' already exists!"
        );


    public function isValid($value)
    {

        $this->_setValue($value);

        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        $accounts = $this->getAccountTypes($domain);

        if (in_array($value, $accounts))  {
            $this->_error(self::EXISTS);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();
    }

    public function getAccountTypes($domain)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG($domain);

        $defaults = $cli->GetAccountDefaults("$domain");

        $sc = array_keys($defaults["ServiceClasses"]);

        return array_combine($sc, $sc);
    }




}