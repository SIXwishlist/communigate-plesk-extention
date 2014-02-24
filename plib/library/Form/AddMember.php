<?php 
/*
*************** 
* Form class for creating Domains
***************
*/
class Modules_Communigate_Form_AddMember extends pm_Form_Simple
{
    /*
    *************** 
    * Initializing for elements
    * with validators
    ***************
    */
    public function init()
    {
        $myValidator = new Modules_Communigate_Validators_UniqueAA();
        $group = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'group', null );
        // $group = $request->_getParam('group');
        $accounts = $this->getAccounts();

        $this->addElement('select', 'account', array(
            'label' => 'Email address:',
            'multiOptions' => $accounts,
            // 'value' =>  $accounts,
            // 'required' => true,
            ));

        $this->addElement('submit', 'addMemberToGroup', array(
            'ignore'   => true,
            'label'    => 'Add',
        ));
    }
 
    /*
    *************** 
    * Form processing and creating forwarders
    * If domain is writen it is stripped
    ***************
    */   
    public function process($group)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        Modules_Communigate_Custom_Groups::addMember($group , $this->getValue('account'));
    }

    public function getAccounts()
    {
        
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        
        $accounts = array_keys($cli->ListAccounts($domain));
        foreach ($accounts as $index => $account) {
            $accounts[$index] = $account . "@$domain";
        }

        return  array_combine($accounts, $accounts);

    }


}
?>