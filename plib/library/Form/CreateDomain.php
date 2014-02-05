<?php 
/*
*************** 
* Form class for creating Domains
***************
*/
class Modules_Communigate_Form_CreateDomain extends pm_Form_Simple
{
    public function init()
    {
        $myValidator = new Modules_Communigate_Validators_UniqueDomain;

        $this->addElement('text', 'domainName', array(
            'label' => 'Domain Name',
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                array($myValidator, true),
            ),
        ));


        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/index/index",
        ));
    }
    public function process()
    {
        $domainName = $this->getValue('domainName');
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();

                        
        // $settings = $cli->GetDomainDefaults();

        $cli->CreateDomain($domainName);
    }
}


?>