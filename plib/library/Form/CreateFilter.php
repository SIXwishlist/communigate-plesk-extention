<?php 
/*
*************** 
* Form class for creating Domains
***************
*/
class Modules_Communigate_Form_CreateFilter extends pm_Form_Simple
{
    /*
    *************** 
    * Initializing for elements
    * with validators
    ***************
    */
    public function init()
    {
        // $group = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'group', null );
        $optionsForPriority = array('0' => 'Inactive', '1' => '1', '2' => '2', '3' => '3',
        '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9', '10' => 'Higheset');

        $optionsForDataFilter = array('---','From', 'Sender', 'Return-Path',
            'To', 'Cc', 'Any To or Cc', 'Each To or Cc', 'Reply-To', "'From' Name",
            'Subject', 'Message-ID', 'Message Size', 'Time Of Day', 'Current Date',
            'Current Day', 'Preference', 'FreeBusy', 'Human Generated', 'Header Field',
            'Any Recipient', 'Each Recipient', 'Existing Mailbox', 'Security',
            'Source', 'Submit Address'
            );
        $optionsForOperations = array('is', 'is not', 'in', 'greater than', 'less than');
        $optionsForActions = array('---', 'Store in', 'Mark', 'Add Header', 'Tag Subject',
            'Reject with', 'Discard', 'Stop Processing', "Remember 'From' in",
            'Access Request', 'Accept Reply', 'Store Encrypted in', 'Copy Attachments into',
            'Redirect To', 'Forward to', 'Mirror to', 'Reply with', 'Reply with All with',
            'React with', ' Send IM', 'Execute URL', 'Execute', 'FingerNotify');

        $this->addElement('text', 'name', array(
            'label' => 'Filter Name',
            // 'value' => pm_Settings::get('name'),
            'required' => true,
            'description' => 'The filter name must be unique. If you give the filter the same name as another filter, the previous filter will be overwritten.',
            'validators' => array(
                array('NotEmpty', true),
                ),
            ));

        $this->addElement('select', 'priority', array(
            'label' => 'Priority',
            'multiOptions' => $optionsForPriority,
            ));

        $this->addElement('select', 'dataFilter', array(
            'label' => 'Rules',
            'multiOptions' => array_combine($optionsForDataFilter, $optionsForDataFilter),
            // 'value' => pm_Settings::get('exampleSelect'),
            // 'required' => true,
            ));

        $this->addElement('select', 'oprationFilter', array(
            'label' => '',
            'multiOptions' => array_combine($optionsForOperations, $optionsForOperations),
            // 'value' => pm_Settings::get('exampleSelect'),
            // 'required' => true,
            ));

        $this->addElement('text', 'parameterFilter', array(
            'label' => '',
            // 'value' => pm_Settings::get('name'),
            ));

        $this->addElement('button', 'addFilter', array(
            'ignore'   => true,
            'label'    => '+',
            ));
        $this->addElement('button', 'removeFilter', array(
            'ignore'   => true,
            'label'    => '-',
            ));

        $this->addElement('select', 'actionFilter', array(
            'label' => 'Actions',
            'multiOptions' => array_combine($optionsForActions, $optionsForActions),
            // 'value' => pm_Settings::get('exampleSelect'),
            // 'required' => true,
            ));

        $this->addElement('text', 'actionParameter', array(
            'label' => '',
            // 'value' => pm_Settings::get('exampleSelect'),
            // 'required' => true,
            ));

        $this->addElement('button', 'addAction', array(
            'ignore'   => true,
            'label'    => '+',
            ));
        $this->addElement('button', 'removeAction', array(
            'ignore'   => true,
            'label'    => '-',
            ));

        $this->getElement('dataFilter')->setAttrib('class', 'changeWidth');
        $this->getElement('actionFilter')->setAttrib('class', 'changeWidth');
        
        $this->getElement('oprationFilter')->setAttrib('class', 'posRight');
        $this->getElement('actionParameter')->setAttrib('class', 'posRight');

        $this->getElement('parameterFilter')->setAttrib('class', 'posDown');

        $this->getElement('priority')->setAttrib('class', 'prioritClass');

        $this->getElement('addAction')->setAttrib('class', 'addActionClass');
        $this->getElement('removeAction')->setAttrib('class', 'removeActionClass');

        $this->addControlButtons(array(
            'cancelLink' => "/modules/communigate/index.php/filter/index",
            ));

    }
   
    public function process($group)
    {
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
    }
}
?>