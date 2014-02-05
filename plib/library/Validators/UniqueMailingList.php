<?php  
/*
*************** 
* Class for validating the mailing list creating form
***************
*/
class Modules_Communigate_Validators_UniqueMailingList extends Zend_Validate_Abstract
{


    const EXISTS = 'exists';
 
    protected $_messageTemplates = array(
        self::EXISTS => "The mailing list '%value%' already exists!"
    );

     
    public function isValid($value)
    {

        $this->_setValue($value);
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

        // Checking if the passed account exists in accounts
        $lists = $cli->ListLists($domain);
        if (in_array($value, $lists))  {
            $this->_error(self::EXISTS);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();


    }

}