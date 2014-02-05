<?php  
/*
*************** 
* Class for validating if a domain exits used in InDomains form
***************
*/
class Modules_Communigate_Validators_InDomains extends Zend_Validate_Abstract
{
    public $flag = false;

    const EXISTS = 'exists';
 
    protected $_messageTemplates = array(
        self::EXISTS => "The domain '%value%' doesn't exist"
    );

    function __construct($flag) {
        $this->flag = $flag;
    }
 
    public function isValid($value)
    {
        $this->_setValue($value);

        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $domains = $cli->ListDomains();
        if (!in_array($value, $domains)) {
            $this->_error(self::EXISTS);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();


    }
}