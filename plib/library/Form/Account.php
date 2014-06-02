<?php
/*
*************** 
* A Form class for creating and updating accounts
***************
*/
class Modules_Communigate_Form_Account extends pm_Form_Simple
{

    public function init()
    {

        $account = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'account', null );
        $myValidator = new Modules_Communigate_Validators_UniqueAA();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        $this->addElement('text', 'name', array(
            'label' => 'Name',
            'value' => pm_Settings::get('name'),
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                ),
            ));
        
        $this->addElement('text', 'accountName', array(
            'label' => 'Account Name',
            'value' => pm_Settings::get('accountName'),
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                array($myValidator, true),
                )
            ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
            'value' => '',
            'description' => 'Password',
            'validators' => array(
                array('NotEmpty', true),
                array('StringLength', true, array(6, 255)),
                ),
            ));
        $this->addElement('select', 'accountType', array(
            'label' => 'Account Type',
            'multiOptions' => $this->getAccountTypes($domain),
            'value' => pm_Settings::get('exampleSelect'),
            // 'required' => true,
            ));



    // Prepopulate form for updating account
    if ($this->account !== '') {

        $acc = new Modules_Communigate_Custom_Accounts($domain);
        $data = $acc->getAccountData($account);
        $this->populate($data);
    }

    $this->addControlButtons(array(
        'cancelLink' => "/modules/communigate/index.php/index/list-accounts",
    ));


    }

    public function process()
    {   
        
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $acc = new Modules_Communigate_Custom_Accounts($domain);
        $account = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'account', null );


        // Form for update
        if ($account !== null) {
            $acc->updateAccount($account,
            $this->getValue('accountType'),
            $this->getValue('name'),
            $this->getValue('password'),
            $this->getValue('accountName'));
        // Form for create
        } else {
            $acc->createAccount($this->getValue('accountName'),
            $this->getValue('accountType'),
            $this->getValue('name'),
            $this->getValue('password'));

        }
    }

    public function getAccountTypes($domain)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG($domain);

        $defaults = $cli->GetAccountDefaults($domain);
        $serverDefaults = $cli->SendCommand('GETSERVERACCOUNTDEFAULTS');
        $serverDefaults = $cli->parseWords($cli->getWords());


        if (empty($defaults['ServiceClasses']) &&  empty($serverDefaults["ServiceClasses"])) {
            return array();   
        } elseif (empty($defaults['ServiceClasses'])) {
            $sc = array_keys($serverDefaults["ServiceClasses"]);
            return array_combine($sc, $sc);
        } elseif (empty($serverDefaults["ServiceClasses"])) {
            $sc = array_keys($defaults["ServiceClasses"]);
            return array_combine($sc, $sc);
        } else {
            $sc = array_keys($defaults["ServiceClasses"]);
            $defsc = array_keys($serverDefaults["ServiceClasses"]);
            $toRet = array_merge($sc, $defsc);
            return array_combine($toRet, $toRet);
        }
    }
}
?>