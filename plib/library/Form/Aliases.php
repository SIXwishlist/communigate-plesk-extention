<?php 
/*
*************** 
* A form class for creating aliases
***************
*/
class Modules_Communigate_Form_Aliases extends pm_Form_Simple
{
    
    public function init()
    {

        $myValidator = new Modules_Communigate_Validators_UniqueAA();

        $this->addElement('text', 'name', array(
            'label' => 'Alias Name',
            'value' => pm_Settings::get('name'),
            'validators' => array(
                array('NotEmpty', true),
                array($myValidator, true)
            ),
        ));
        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/index/list-Aliases",
        ));
        
    }
    public function process()
    {
        $account = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'account', null );

            Modules_Communigate_Custom_Accounts::createAlias($account,
            $this->getValue('name')
            );
    }

}