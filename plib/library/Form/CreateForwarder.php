<?php 
/*
*************** 
* Form class for creating Domains
***************
*/
class Modules_Communigate_Form_CreateForwarder extends pm_Form_Simple
{
    /*
    *************** 
    * Initializing for elements
    * with validators
    ***************
    */
    public function init()
    {
        $accountBelongs = new Modules_Communigate_Validators_AccountBelongToDomain;
        $uniqueForwarder = new Modules_Communigate_Validators_UniqueForwarder;
        $mailValidator = new Zend_Validate_EmailAddress();

        $this->addElement('text', 'addressToForward', array(
            'label' => 'Address to Forward:',
            'required' => true,
            'description' => 'Domain is automatically added',
            'validators' => array(
                array('NotEmpty', true),
                array($accountBelongs, true),
                array($uniqueForwarder, true),
                ),
            ));
        $this->addElement('text', 'destination', array(
            'label' => 'Forward to email address:',
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                array($mailValidator, true),
                ),
            ));


        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/forwarders/index",
            ));
    }
 
    /*
    *************** 
    * Form processing and creating forwarders
    * If domain is writen it is stripped
    ***************
    */   
    public function process()
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        // Проверка дали потребителя не е въвел и домейна към акаунта
        // ако е въвел то въведеното си остава, ако не е то домейна се добавя
        if (strpos($this->getValue('addressToForward'), "@$domain")) {
            $addressToForward = $this->getValue('addressToForward');
        } else {
            $addressToForward = $this->getValue('addressToForward') . "@$domain";
        }
     
        $destination = $this->getValue('destination');

        Modules_Communigate_Custom_Forwarders::addForwarder($addressToForward, $destination);
    }
}
?>