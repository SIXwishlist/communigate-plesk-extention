<?php
/*
*************** 
* A Form class for adding email archiving
***************
*/
class Modules_Communigate_Form_EmailArchiving extends pm_Form_Simple
{

    public function init()
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $settings = $this->getSettings($domain);

        $options = array('' => 'default', 0 => 'never', '1d' => '24 hours', '2d' => '2 days',
            '3d' => '3 days', '5d' => '5 days', '7d' => '7 days', '14d' => '2 weeks',
            '30d' => '30 days', '90d' => '90 days', '180d' => '180 days', '365d' => '365 days', '730d' => '730 days',);

        $this->addElement('select', 'archiveMessageAfter', array(
            'label' => 'Archive Message After',
            'multiOptions' => $options,
            'value' =>  $settings[0],
            // 'required' => true,
            ));


        $this->addElement('select', 'deleteMessageAfter', array(
            'label' => 'Delete Message After',
            'multiOptions' => $options,
            'value' =>  $settings[1],
            // 'required' => true,
            ));


        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/index/list-accounts",
            ));


    }

    public function process()
    {   

        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG($domain);

        $cli->SetAccountDefaults(array('domainName'=>$domain, 'settings' => array(
            'ArchiveMessagesAfter' => $this->getValue('archiveMessageAfter'),
            'DeleteMessagesAfter' => $this->getValue('deleteMessageAfter')
            )));

        $cli->Logout();
    }

    public function getSettings($domain)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG($domain);
        $settings = $cli->GetAccountDefaults($domain);
        $archive = '';
        $delete ='';

        if (isset($settings['ArchiveMessagesAfter'])) {
            $archive = $settings['ArchiveMessagesAfter'];
        }
        if (isset($settings['DeleteMessagesAfter'])) {
            $delete = $settings['DeleteMessagesAfter'];
        }
        return array($archive, $delete);
    }



}





?>