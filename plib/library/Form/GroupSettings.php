<?php 
/*
*************** 
* Form class for creating Domains
***************
*/
class Modules_Communigate_Form_GroupSettings extends pm_Form_Simple
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
        $group = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'group', null );
        // $group = $request->_getParam('group');
        $settings = $this->getSelectedSettings($group);

        $preselect = $settings[0];
        $realName= $settings[1];


        $options = array('EmailDisabled' => 'Disable E-mails',
        'Expand' => 'Expand Member Groups',
        'FinalDelivery' => 'Report Delivery to Group',
        'RejectAutomatic' => 'Reject Automatic Messages',
        'RemoveAuthor' => 'Remove Author from Distribution',
        'RemoveToAndCc' => 'Remove To and Cc from Distribution',
        'SetReplyTo' => 'Set Reply-To to Group',
        'SignalDisabled' => 'Disable Signals');

        $this->addElement('text', 'groupName', array(
            'label' => 'Group Name:',
            'value' => $realName,
            'required' => false,
            ));

        $this->addElement ('multiCheckbox', 'settings', 
            array (
            'label' => 'Settings:',
            'value' => $preselect, // select these 2 values
            'multiOptions' => $options
        )
            );

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
    public function process($group)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');

        Modules_Communigate_Custom_Groups::changeSettingsForGroup($group, $this->getValue('settings'),$domain, $this->getValue('groupName'));
    }

    /**
     * Method to getting the group setting to be checked
     * @param  string $group name og group with added domain
     * @return array        elements to be selected
     */
    private function getSelectedSettings($group){

        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $settings = $cli->GetGroup($group);
        $preselect= array();
        foreach ($settings as $setting => $checked) {
            if ($checked === 'YES') {
                $preselect[] = $setting;
            }
        }
        if (isset($settings['RealName'])) {
            $name = $settings['RealName'];
        } else {
            $name = '';
        }
        

        return array($preselect, $name);

    }


}
?>