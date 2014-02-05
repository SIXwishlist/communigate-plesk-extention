<?php  
/*
*************** 
* Class for validating a possword for account
***************
*/
class Modules_Communigate_Validators_ValidPassword extends Zend_Validate_Abstract
{


    const correct = 'correct';
 
    protected $_messageTemplates = array(
        self::correct => "You have entered a incorrect password"
    );

     
    public function isValid($value, $context = null)
    {

        $this->_setValue($value);

        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $account = $context['accountName'] . "@$domain";
        $password = $value;
        if ($cli->VerifyAccountPassword($account, $password) === 'incorrect') {
            $this->_error(self::correct);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();


    }

}