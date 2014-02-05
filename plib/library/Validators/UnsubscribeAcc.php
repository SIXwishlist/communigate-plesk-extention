<?php  
/*
*************** 
* Validator class for validating account before unsubscribing for a maliling list 
***************
*/
class Modules_Communigate_Validators_UnsubscribeAcc extends Zend_Validate_Abstract
{


    const EXISTS = 'exists';
 
    protected $_messageTemplates = array(
        self::EXISTS => "The account '%value%' doesn't exist or is not subscribed to this mailing list!"
    );

     
    public function isValid($value)
    {

        $this->_setValue($value);
         $list = Zend_Controller_Front::getInstance()->getRequest()->getParam('list');
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        // Checking if the passed account exists in accounts
        $accounts = array_keys($cli->ListAccounts($domain));
        $subscribers = $cli->ListSubscribers("$list@$domain");
        // var_dump($subscribers, $accounts );
        if (!in_array($value, $accounts) || !in_array("$value@$domain", $subscribers))  {
            $this->_error(self::EXISTS);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();


    }

}