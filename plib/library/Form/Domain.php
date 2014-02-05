<?php 
/*
*************** 
* A form class for entering a domain and validating it
* for entering the application
***************
*/
class Modules_Communigate_Form_Domain extends pm_Form_Simple
{
    public function init()
    {
        $myValidator = new Modules_Communigate_Validators_InDomains();

        $this->addElement('text', 'domain', array(
            'label' => 'Domain Name',
            'required' => true,
            'validators' => array(
                array('NotEmpty', true),
                array($myValidator, true),
            ),
        ));


        $this->addControlButtons(array(
            'cancelLink' => pm_Context::getBaseUrl(),
        ));
    }
    public function process()
    {

    }
}





?>