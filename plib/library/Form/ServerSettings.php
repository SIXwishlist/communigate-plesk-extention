<?php 
/*
*************** 
* A form class for creating aliases
***************
*/
class Modules_Communigate_Form_ServerSettings extends pm_Form_Simple
{

    public function init()
    {

        $host = pm_Settings::get('host');
        $port = pm_Settings::get('port');
        $login = pm_Settings::get('userName');
        $password = pm_Settings::get('password');
        $webmail = pm_Settings::get('webMail');

        // $myValidator = new Modules_Communigate_Validators_UniqueAA();

        $this->addElement('text', 'host', array(
            'label' => 'Host',
            'reqired' => true,
            'value' => $host,
            'validators' => array(
                array('NotEmpty', true),
                // array($myValidator, true)
                ),
            ));
        $this->addElement('text', 'port', array(
            'label' => 'Port',
            'reqired' => true,
            'value' => $port,
            'validators' => array(
                array('NotEmpty', true),
                // array($myValidator, true)
                ),
            ));
        $this->addElement('text', 'userName', array(
            'label' => 'User Name',
            'reqired' => true,
            'value' => $login,
            'validators' => array(
                array('NotEmpty', true),
                // array($myValidator, true)
                ),
            ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
            'reqired' => true,
            'value' => $password,
            'validators' => array(
                array('NotEmpty', true),
                // array($myValidator, true)
                ),
            ));
        $this->addElement('text', 'webmail', array(
            'label' => 'Web Mail',
            'value' => $webmail,
            'reqired' => true,
            'validators' => array(
                array('NotEmpty', true),
                // array($myValidator, true)
                ),
            ));
        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/index/index",
            ));
        
    }
    public function process()
    {
        pm_Settings::set('host', $this->getValue('host'));
        pm_Settings::set('port', $this->getValue('port'));
        pm_Settings::set('userName', $this->getValue('userName'));
        pm_Settings::set('password', $this->getValue('password'));
        pm_Settings::set('webMail', $this->getValue('webmail'));

    }

}