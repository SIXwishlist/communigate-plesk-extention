<?php 
/*
*************** 
* Form to verify the ownership of an account before unsubscribing from a mailing list
***************
*/
class Modules_Communigate_Form_Unsubscribe extends pm_Form_Simple
{
    
    public function init()
    {   
    $myValidator = new Modules_Communigate_Validators_UnsubscribeAcc(); 
    $passValidator = new Modules_Communigate_Validators_ValidPassword();

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
            array($passValidator, true),
        ),
    ));        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/list/index",
    ));
    }
    public function process()
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $account = $this->getValue('accountName') . "@$domain";
        $password = $this->getValue('password');
        return $cli->VerifyAccountPassword($account, $password);
        $cli->Logout();
    }

}