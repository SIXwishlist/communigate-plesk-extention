<?php  
/*
*************** 
* Class For validating if an forwarder with that name exists
***************
*/
class Modules_Communigate_Validators_UniqueForwarder extends Zend_Validate_Abstract
{
    const UNIQUE = 'unique';
    
    protected $_messageTemplates = array(
        self::UNIQUE => "Forwarder '%value%' already exists!"
        );
    
    public function isValid($value)
    {
        $this->_setValue($value);

        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        $forwarders = $cli->ListForwarders($domain);



        if (in_array($value, $forwarders)) {
            $this->_error(self::UNIQUE);
            return false;
        } else {
            return true;
        }   

        $cli->Logout();
    }
}