<?php  

/*
*************** 
* Validator class for validating if value passed -> a account@domain is in the current domain
* Used in forwarder creation if the user specifyes account not in the domain
***************
*/
class Modules_Communigate_Validators_AccountBelongToDomain extends Zend_Validate_Abstract
{
    const EXISTS = 'exists';

    protected $_messageTemplates = array(
        self::EXISTS => "The email '%value%' doesn't belong to this domain"
        );

    public function isValid($value)
    {
        $this->_setValue($value);
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

        $whatIWant = substr($value, strpos($value, "@"));

        if (strpos($value, "@")) {
            if ($whatIWant === "@$domain"){
                return true;
            } else {
                $this->_error(self::EXISTS);
                return false;
            }
        } else {
            return true;
        }

        $cli->Logout();
    }
}