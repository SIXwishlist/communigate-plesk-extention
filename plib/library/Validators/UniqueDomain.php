<?php  
/*
*************** 
* Class for validating the creation of domain
***************
*/
class Modules_Communigate_Validators_UniqueDomain extends Zend_Validate_Abstract
{
    const EXISTS = 'exists';
 
    protected $_messageTemplates = array(
        self::EXISTS => "The domain '%value%' already exist"
    );
 
    public function isValid($value)
    {
        $this->_setValue($value);

        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $domains = $cli->ListDomains();
        if (in_array($value, $domains)) {
            $this->_error(self::EXISTS);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();


    }
}