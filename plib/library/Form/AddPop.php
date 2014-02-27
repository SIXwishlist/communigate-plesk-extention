<?php
/*
*************** 
* A Form class for creating and updating RPOPS
***************
*/
class Modules_Communigate_Form_AddPop extends pm_Form_Simple
{

    public function init()
    {
        $req = true;
        if (Zend_Controller_Front::getInstance()->getRequest()->getActionName() == 'view-Edit') {       
            $pop = new Modules_Communigate_Custom_RemotePop;
            $account = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'account', null );
            $name = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'name', null );
            $displayName = $name;
            $data = $pop->getPopInfo($name, $account);
            $req = false;

            $valuesForMulty = array();
            if ($data['APOP'] == '1' || $data['APOP'] == 'YES') {
                array_push($valuesForMulty, 'apop');
            }
            if ($data['TLS'] == '1' || $data['TLS'] == 'YES') {
                array_push($valuesForMulty, 'tls');
            }
            if ($data['leave'] == '1' || $data['leave'] == 'YES') {
                array_push($valuesForMulty, 'leave');
            }
        }

        $options = array('2m' => '2 Minutes', '3m' => '3 Minutes',
         '5m' => '5 Minutes', '10m' => '10 Minutes', '15m' => '15 Minutes',
         '20m' => '20 Minutes', '30m' => '30 Minutes', '1h' => '1 Hour',
         '2h' => '2 Hours', '3h' => '3 Hours', '5h' => '5 Hours',
         '6h' => '6 Hours', '8h' => '8 Hours', '1d' => '24 Hours');

        $this->addElement('text', 'displayName', array(
            'label' => 'Display Name',
            'value' => $displayName,
            'required' => $req,
            // 'attribs' => array('readonly' => ''),
            'validators' => array(
                array('NotEmpty', true),
                ),
            ));
        
        $this->addElement('text', 'account', array(
            'label' => 'Account',
            'value' => $data['authName'],
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                )
            ));

        $this->addElement('text', 'host', array(
            'label' => 'Host',
            'value' => $data['domain'],
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                )
            ));

        $this->addElement('password', 'password', array(
            'label' => 'Password',
            'renderPassword' => true,
            'value' => $data['password'],
            'required' => $req,
            'validators' => array(
                array('NotEmpty', true),
                ),
            ));
        $this->addElement('text', 'mailbox', array(
            'value' => $data['mailbox'],
            'label' => 'MailBox',
            ));

        $this->addElement('multiCheckbox',
        'settings', // name
        array(
            'label' => '',
            'value' => $valuesForMulty,
            'multiOptions' => array('leave' => 'Leave Message On Server',
               'apop' => 'APOP', 'tls' => 'TLS'),
            ));

        $this->addElement('select', 'pullEvery', array(
            'label' => 'Pull Every',
            'multiOptions' => $options,
            'value' => $data['period'],
            ));

        if (Zend_Controller_Front::getInstance()->getRequest()->getActionName() !== 'create') {
            $this->getElement('displayName')->setAttrib('readonly', 'true');
        }
        
        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/remote-Pop/index",
            ));
       }

    public function process()
    {   
        $apop = (in_array('apop', $this->getValue('settings')) ? true : false);
        $tls = (in_array('tls', $this->getValue('settings')) ? true : false);
        $leave = (in_array('leave', $this->getValue('settings')) ? true : false);
        
        $name =  $this->getValue('displayName');
        if (Zend_Controller_Front::getInstance()->getRequest()->getActionName() !== 'create') {
            $name = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'name', null );
        }

        $account = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'account', null );
        $details = array(
            $name => array(
                'APOP' => $apop,
                'TLS' => $tls,
                'leave' => $leave,
                'authName' => $this->getValue('account'),
                'domain' => $this->getValue('host'),
                'mailbox' => $this->getValue('mailbox'),
                'password' => $this->getValue('password'),
                'period' => $this->getValue('pullEvery'),
                )
            );

        $pop = new Modules_Communigate_Custom_RemotePop;
        $pop-> addRemotePop($account, $details);
    }

}
?>