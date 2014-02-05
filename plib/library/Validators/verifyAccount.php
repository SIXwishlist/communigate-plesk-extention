<?php  

/*
*************** 
* Validator class verifying if the user is the user has created the mailing list
***************
*/
class Modules_Communigate_Validators_verifyAccount extends Zend_Validate_Abstract
{


    const EXISTS = 'exists';
 
    protected $_messageTemplates = array(
        self::EXISTS => "You are not the owner of the mailing list!"
    );

     
    public function isValid($value, $context =  null)
    {
        
        $account = Zend_Controller_Front::getInstance()->getRequest()->getParam('account');
        $this->_setValue($value);
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
 
        $parts = explode("@",$account);  

        $account = $parts['0']; 
        rtrim($accounr, "@");


        if ($value !== $account) {
            $this->_error(self::EXISTS);
            return false;
        } else {
            return true;
        } 

         $cli->Logout();






        // // Checking if the passed account exists in accounts and  if
        // // it's already subscribed to this list
        // $subscribers = $cli->ListSubscribers("$list@$domain");
        // $accounts = array_keys($cli->ListAccounts($domain));
        // if ((!in_array($value, $accounts)) || in_array("$value@$domain", $subscribers)) {
        //     $this->_error(self::EXISTS);
        //     return false;
        // } else {
        //     return true;
        // }   

       


    }

}