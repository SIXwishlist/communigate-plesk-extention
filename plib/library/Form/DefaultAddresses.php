<?php 
/*
*************** 
* A form class setting default addresses for a domain
***************
*/
class Modules_Communigate_Form_DefaultAddresses extends pm_Form_Simple
{
    public function init()
    {
        // $myValidator = new Modules_Communigate_Validators_InDomains();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $defaultBehaviors = array(
            '' => '--',
            'Discarded' => 'Discard Email',
            'Rejected' => 'Reject Email',
            'Rerouted to' => 'Forward to this address:',
            'Accepted and Bounced' => 'Accept and Bounce'
            );


        $this->addElement('text', 'domain', array(
            'label' => 'Domain Name',
            'readonly' => true,
            'value' => $domain,
            'validators' => array(
                array('NotEmpty', true),

                ),
            ));

        $this->addElement('select', 'MailToUnknown', array(
            'label' => 'Set Default Behavior',
            'multiOptions' => $defaultBehaviors,
            // 'required' => true,
            ));

        $this->addElement('text', 'MailRerouteAddress', array(
            'label' => 'Address to forward to',
            ));

        $this->populate($this->getSettings());

        $this->addControlButtons(array(
            'cancelLink' => pm_Context::getBaseUrl(),
            ));
    }
    public function process()
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        $domainData = array(
            "domainName" => $domain,
            "settings" => array(
               "MailToUnknown" => $this->getValue('MailToUnknown'),
               'MailRerouteAddress' => $this->getValue('MailRerouteAddress')
               )
            );
        // $cli->setDebug(1);
        $cli->UpdateDomainSettings($domainData);
        $cli->Logout();
    }

    private function getSettings()
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        // $cli->setDebug(1);
        $settings = $cli->GetDomainSettings($domain);
        return array('MailToUnknown' => $settings['MailToUnknown'], 'MailRerouteAddress' => $settings['MailRerouteAddress']);
    }



}

?>