<?php 
/*
*************** 
* A form class for creating and updating Auto Responders
***************
*/
class Modules_Communigate_Form_AutoResponders extends pm_Form_Simple
{

    public function init()
    {
        $account = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'autoResponder', null );
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $myValidator = new Modules_Communigate_Validators_Account();

        $this->addElement('text', 'email', array(
            'label' => 'Email',
            'required' => true,
            'description' => 'Domain is automatically added',
            'validators' => array(
                array('NotEmpty', true),
                array($myValidator, true)
                ),
            ));
        $this->addElement('text', 'from', array(
            'label' => 'From',
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                ),
            ));
        $this->addElement('text', 'subject', array(
            'label' => 'Subject',
            'required' => true,
            'size' => '39',
            'validators' => array(
                array('NotEmpty', true),
                ),
            ));
        $this->addElement('textarea', 'body', array(
            'label' => "Body",
            'required' => true,
            'rows' => array('size' => 8),
            'validators' => array(
                array('NotEmpty', true),
                )
            ));

        $this->addElement('text', 'endDate', array(
            'label' => 'Ends',
            'description' => 'You can specify time by adding it in hh:mm:ss format',
            'required' => true,
            'id' => 'datepick',
            'validators' => array(
                array('NotEmpty', true),
                ),
            ));

        // Prepopulate form for updating auto responder
        if ($this->account !== '') {
            $autoResponder = new Modules_Communigate_Custom_AutoResponders($domain);
            $data = $autoResponder->getAutoResponderData($account);
            $this->populate($data);

        }



        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/auto-Responders/index",
            ));

    }
    public function process()
    {
        // 
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        if (strpos($this->getValue('email'), "@$domain")) {
            $email = $this->getValue('email');
        } else {
            $email = $this->getValue('email'). "@$domain";
        }

        $bodyWithRemovedNewLines = str_replace("\r\n", '\\e', $this->getValue('body'));

        Modules_Communigate_Custom_AutoResponders::addAutoResponder(
            $email,
            $this->getValue('endDate'),
            $this->getValue('subject'),
            $this->getValue('from'),
            $bodyWithRemovedNewLines

            );
    }

}