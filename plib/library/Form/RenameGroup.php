<?php 
/*
*************** 
* Form class for creating Domains
***************
*/
class Modules_Communigate_Form_RenameGroup extends pm_Form_Simple
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

        $this->addElement('text', 'groupMail', array(
            'label' => 'New Group Name',
            'required' => true,
            'description' => 'Domain is automatically added',
            'validators' => array(
                array('NotEmpty', true),
                array($myValidator, true),
                ),
            ));

        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/group/index",
            ));
    }
 
    /*
    *************** 
    * Form processing and creating forwarders
    * If domain is writen it is stripped
    ***************
    */   
    public function process($oldGroupMail)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        // Проверка дали потребителя не е въвел и домейна към акаунта
        // ако е въвел то въведеното си остава, ако не е то домейна се добавя
        if (strpos($this->getValue('groupMail'), "@$domain")) {
            $newGroupMail = $this->getValue('groupMail');
        } else {
            $newGroupMail = $this->getValue('groupMail') . "@$domain";
        }

        Modules_Communigate_Custom_Groups::renameGroup($domain, $oldGroupMail, $newGroupMail);
    }
}
?>