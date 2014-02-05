<?php 
/*
*************** 
* Form class for setting account types
***************
*/
class Modules_Communigate_Form_SetType extends pm_Form_Simple
{

    public function init()
    {
        $uniqueServiceClass = new Modules_Communigate_Validators_UniqueServiceClass;
        $accessModesDecorator = new Modules_Communigate_Helpers_Decorator();

        $accessModes = array('Mail' => 'Mail','Relay' => 'Relay','Signal' => 'Signal',
            'Mobile' => 'Mobile','TLS' => 'TLS','POP' => 'POP','IMAP' => 'IMAP',
            'MAPI' => 'MAPI', 'AirSync' => 'AirSync','SIP' => 'SIP','XMPP' => 'XMPP','WebMail' => 'WebMail',
            'XIMSS' => 'XIMSS','FTP' => 'FTP','ACAP' => 'ACAP','PWD' => 'PWD','LDAP' => 'LDAP',
            'RADIUS' => 'RADIUS','S/MIME' => "'S/MIME'",'WebCAL' => 'WebCAL','WebSite' => 'WebSite',
            'PBX' => 'PBX','HTTP' => 'HTTP','MobilePBX' => 'MobilePBX', 'GIPS Media' => 'XMedia',
            'YMedia' => 'YMedia','MobilePronto' => 'MobilePronto'); 

        $this->addElement('text', 'name', array(
            'label' => 'Name',
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                array($uniqueServiceClass, true),
                ),
            ));

        $this->addElement('multiCheckbox',
        'AccessModes', // name
        array(
            'label' => 'Access Modes',
            // 'value' => $valuesForSTA,
            'multiOptions' => $accessModes,
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                ),
            'decorators' => array($accessModesDecorator),
            ));

        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/index/list",
            ));
    }

    
    public function process()
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $this->setAccountTypes($domain);
    }

    public function getServiceClasses($domain)
    {
        $result = "";
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG($domain);
        $accountDefaults = $cli->GetAccountDefaults($domain); 
        foreach ($accountDefaults as $serviceClasses) {
            foreach ($serviceClasses as $nameOfClass => $settings) {
                // Remaping some of the access modes cuz the
                // server doesn't recognize them
                if (in_array("S/MIME", $settings['AccessModes'])) {
                    $key = array_search("S/MIME", $settings['AccessModes']);
                    $settings['AccessModes'][$key] = "\"S/MIME\"";
                }
                $accessModes = implode(", ", $settings['AccessModes']);
                $result .= sprintf("%s={AccessModes=(%s);};", $nameOfClass, $accessModes);
                
            }
        }
        return $result;
    }

    public function setAccountTypes($domain)
    {

        $serviceClasses = $this->getServiceClasses($domain);
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG($domain);
        // $cli->setDebug(1);
        $modes = $this->getValue('AccessModes');

        // Remaping some of the access modes cuz the
        // server doesn't recognize them
        if (in_array("S/MIME", $modes)) {
            $key = array_search("S/MIME", $modes);
            $modes[$key] = "\"S/MIME\"";
        }
        if (in_array("GIPS Media", $modes)) {
            $key = array_search("GIPS Media", $modes);
            $modes[$key] = "XMedia";
        }

        $accessModes = implode(", ", $modes);

        $serviceClassName = $this->getValue('name');

        $serviceClasses .= sprintf("%s={AccessModes=(%s);};", $serviceClassName, $accessModes);

        $command = sprintf("UpdateAccountDefaults $domain {ServiceClasses={%s};}", $serviceClasses);

        $cli->send($command);
        $cli->_parseResponse();
        $cli->Logout();

    }



}


?>