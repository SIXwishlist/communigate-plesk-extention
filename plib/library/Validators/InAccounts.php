<?php  

/*
*************** 
* Validator class for validating if a given account exists or is already subscribed to 
* the given mailing list
***************
*/
class Modules_Communigate_Validators_InAccounts extends Zend_Validate_Abstract
{


    const EXISTS = 'exists';
 
    protected $_messageTemplates = array(
        self::EXISTS => "The account '%value%' doesn't exist or it's already subscribed!"
    );

     
    public function isValid($value)
    {
        
        $list = Zend_Controller_Front::getInstance()->getRequest()->getParam('list');
        $this->_setValue($value);
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

        // Checking if the passed account exists in accounts and  if
        // it's already subscribed to this list
        $subscribers = $cli->ListSubscribers("$list@$domain");
        $accounts = array_keys($cli->ListAccounts($domain));
        if ((!in_array($value, $accounts)) || in_array("$value@$domain", $subscribers)) {
            $this->_error(self::EXISTS);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();


    }

}