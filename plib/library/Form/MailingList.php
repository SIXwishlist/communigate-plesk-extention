<?php 
/*
*************** 
* A form class for creating a mailing list
***************
*/
class Modules_Communigate_Form_MailingList extends pm_Form_Simple
{
    
    public function init()
    {

    $validPass = new Modules_Communigate_Validators_ValidPassword();
    $existingAccount = new Modules_Communigate_Validators_Account();
    $uniqueMailingList = new Modules_Communigate_Validators_UniqueMailingList();

    $this->addElement('text', 'listAddress', array(
        'label' => 'Mailing List Address',
        'required' => true,
        'validators' => array(
            array('NotEmpty', true),
            array($uniqueMailingList, true),
        )
    ));
    $this->addElement('text', 'accountName', array(
    'label' => "Administrator's Account",
    'required' => true,
    'validators' => array(
        array('NotEmpty', true),
        array($existingAccount, true),
    )
    ));
    $this->addElement('password', 'password', array(
        'label' => 'Password',
        'value' => '',
        'description' => 'Password',
        'validators' => array(
            array('NotEmpty', true),
            array('StringLength', true, array(6, 255)),
            array($validPass, true)
        ),
    ));
    $this->addControlButtons(array(
    'cancelLink' => "/modules/communigate/index.php/list/index",
    ));
    }
    
    // 
    public function process()
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $listName = $this->getValue('listAddress') . "@$domain";

        $accountName = $this->getValue('accountName');
        $cli->CreateList($listName, $accountName);
        $cli->Logout();
    }

}