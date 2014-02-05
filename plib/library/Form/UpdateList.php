<?php 
/*
*************** 
* Form class creating the form for updating mailing list
***************
*/
class Modules_Communigate_Form_UpdateList extends pm_Form_Simple
{
    protected $_url = array();
    
    public function init()
    {

    // $validPass = new Modules_Communigate_Validators_ValidPassword();
    // $existingAccount = new Modules_Communigate_Validators_Account();
    // $uniqueMailingList = new Modules_Communigate_Validators_UniqueMailingList();

    $this->addElement('text', 'Owner', array(
        'label' => 'Owner',

        'readonly' => true,
        'validators' => array(
            array('NotEmpty', true),
            // array($uniqueMailingList, true),
        )
    ));
    $this->addElement('select', 'Subscribe', array(
        'label' => 'Subscribe',
        'multiOptions' => array('anybody' => 'anybody',
                            'locals only' => 'locals only',
                            'this domain only' => 'this domain only',
                            'moderated' => 'moderated',
                            'nobody' => 'nobody'),
        'value' => pm_Settings::get('exampleSelect'),

    ));   
    $this->addElement('text', 'ConfirmationSubject', array(
    'label' => "Confirmation Request Subject",
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('textarea', 'ConfirmationText', array(
    'label' => "Confirmation Request Text",
    'rows' => array('size' => 5),
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('text', 'PolicySubject', array(
    'label' => "Policy Message Subject",
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('textarea', 'PolicyText', array(
    'label' => "Policy Message Text",
    'rows' => array('size' => 5),
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('textarea', 'ListFields', array(
    'label' => "Service Fields",
    'rows' => array('size' => 5),
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('select', 'SizeLimit', array(
        'label' => 'Posting Size Limit',
        'multiOptions' => array('unlimited' => 'unlimited',
                            '0' => '0',
                            '1024' => '1024',
                            '3K' => '3K',
                            '10K' => '10K',
                            '30K' => '30K',
                            '100K' => '100K',
                            '300K' => '300K',
                            '1024K' => '1024K',
                            '3M' => '3M',
                            '10M' => '10M',
                            '300M' => '300M'),
        'value' => pm_Settings::get('exampleSelect'),

    ));   
    $this->addElement('textarea', 'TOCTrailer', array(
    'label' => "Feed Mode Trailer",
    'rows' => array('size' => 5),
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('text', 'WarningSubject', array(
    'label' => "Warning Message Subject",
    'rows' => array('size' => 5),
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('textarea', 'WarningText', array(
    'label' => "Warning Message Text",
    'rows' => array('size' => 5),
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('text', 'ByeSubject', array(
    'label' => "Goodbye Message Subject",
    'rows' => array('size' => 5),
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    $this->addElement('textarea', 'ByeText', array(
    'label' => "Goodbye Message Text",
    'rows' => array('size' => 5),
    'validators' => array(
        array('NotEmpty', true),
        // array($existingAccount, true),
    )
    ));
    

    $this->addControlButtons(array(
    'cancelLink' => "/modules/communigate/index.php/list/index",
    ));
    $list = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'list', null );
    $data = Modules_Communigate_Custom_Lists::getListData($list);
    $this->populate($data);
    }
    
    // 
    public function process()
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();;
        $domain = $request->getCookie('domain');
        $settings = array(//'Owner'=>$this->getValue("Owner"),
                        'Subscribe'=>(string)$this->getValue("Subscribe"),
                        'ConfirmationSubject'=>$this->getValue("ConfirmationSubject"),
                        'ConfirmationText'=>$this->getValue("ConfirmationText"),
                        'PolicySubject'=>$this->getValue("PolicySubject"),
                        'PolicyText'=>$this->getValue("PolicyText"),
                        'ListFields'=>$this->getValue("ListFields"),
                        'SizeLimit'=>$this->getValue("SizeLimit"),
                        'TOCTrailer'=>$this->getValue("TOCTrailer"),
                        'WarningSubject'=>$this->getValue('WarningSubject'),
                        'WarningText'=>$this->getValue("WarningText"),
                        'ByeSubject'=>$this->getValue("ByeSubject"),
                        'ByeText'=>$this->getValue("ByeText"),);
        $list = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'list', null ) . "@$domain";
        $cli->UpdateList($list, $settings);
        $cli->Logout();
    }

    public function setUrl($url) {
        $this->_url = $url;
    }

    public function getUrl() {
        return $this->_url;
    }

}