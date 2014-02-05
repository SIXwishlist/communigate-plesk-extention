<?php
/*
*************** 
 * This is the controller for the auto responders opperations
***************
*/ 
class AutoRespondersController extends pm_Controller_Action
{

    public function init()
    {
        parent::init();

        // Init title for all actions
        $this->view->pageTitle = 'Unified Messaging';

        $mailingList = $this->_helper->url("index", "list");
        $accounts = $this->_helper->url("list-Accounts", "index");

        // Init tabs for all actions
        $this->view->tabs = array(
            array(
                'title' => 'Accounts',
                'link' => $accounts,
                ),
            array(
                'title' => 'Mailing Lists',
                'link' => $mailingList,
                ),
            );
    }

    public function indexAction()
    {
        // The default action will redirect to the list-Auto-Responders action
        $this->_forward('list-Auto-Responders');    
    }

    /*
    *************** 
    * this action is for listing forwadrers
    ***************
    */
    public function listAutoRespondersAction()
    {
        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools; 
        
        $list = $this->_getListAutoResponders();

        $button = "<a href=".$this->_helper->url("create", "auto-Responders")." class=\"btn\">Add Auto Responders</a>";

        $this->view->button = $button;

        $this->view->text = "Auto Responders";

        // List object for pm_View_Helper_RenderList
        $this->view->list = $list;
    }

    /*
    *************** 
    * this action is for creating new auto responder
    ***************
    */
    public function createAction()
    {

        $this->view->text = 'Add Auto Responder';

        $form = new Modules_Communigate_Form_AutoResponders(); 

        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools;

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();
            $this->_status->addMessage('info', 'Auto Responder succesfully created!');
            $this->_helper->json(array('redirect' => $this->_helper->url("index", "auto-Responders")));
        };
        $this->view->form = $form;
    }

    /*
    *************** 
    * this action is for updating auto responder
    ***************
    */
    public function updateAction()
    {

        $this->view->text = 'Update Auto Responder';

        $form = new Modules_Communigate_Form_AutoResponders(); 

        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools;

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();
            $this->_status->addMessage('info', 'Auto Responder succesfully updated!');
            $this->_helper->json(array('redirect' => $this->_helper->url("index", "auto-Responders")));
        };
        $this->view->form = $form;
    }

    /*
    *************** 
    * this action is for deleting auto responders
    ***************
    */
    public function deleteAction()
    {
        $account = $this->_getParam('autoResponder');
        Modules_Communigate_Custom_AutoResponders::deleteAutoResponder($account);

        $this->_status->addMessage('info', "Auto Responder for $account was succesfully deleted!");
        $this->_helper->redirector('list-Auto-Responders', 'auto-Responders');
    }

    /*
    *************** 
    * Method organizing the data for the auto responders list
    ***************
    */
    private function _getListAutoResponders()
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $autoResponders = new Modules_Communigate_Custom_AutoResponders($domain);
        $autoRespondersData = $autoResponders->getSubjectsFromVacationMessages();

        $data = array();

        foreach ($autoRespondersData as $account => $subject) {
          $data[] = array(
            'column-1' => $account,
            'column-2' => $subject,
            'column-3' => 
            $this->addButton('auto-Responders', 'update', 'Update',
                array('autoResponder' => $account)) . '    ' .
            $this->addButton('auto-Responders', 'delete', 'Delete',
                array('autoResponder' => $account), true),
            );
      }

      $list = new pm_View_List_Simple($this->view, $this->_request);
      $list->setData($data);
      $list->setColumns(array(
        'column-1' => array(
            'title' => 'Email',
            'noEscape' => true,
            ),
        'column-2' => array(
            'title' => 'Subject',
            'noEscape' => true,
            ),
        'column-3' => array(
            'title' => 'Functions',
            'noEscape' => true,
            ),
        ));

        // Take into account listDataAction corresponds to the URL /list-data/
      $list->setDataUrl(array('action' => 'list-data'));

      return $list;
    }

    /*
    *************** 
    * Method creating the small tool bar
    ***************
    */
    public function _getSmallTools()
    {
        $smallTools = array(
            array(
                'title' => 'Accounts',
                'description' => 'Example module with UI samples',
                'class' => 'accounts',
                'link' => $this->_helper->url("list-Accounts", "index"),
                ),
            array(
                'title' => 'Aliases',
                'description' => 'Example module with UI samples',
                'class' => 'aliases',
                'link' => $this->_helper->url("list-Aliases", "index"),
                ),
            array(
                'title' => 'Default Addresses',
                'description' => 'Example module with UI samples',
                'class' => 'default-addresses',
                'link' => $this->_helper->url("default-Addresses", "index"),
                ),
            array(
                'title' => 'Forwarders',
                'description' => 'Example module with UI samples',
                'class' => 'forwarders',
                'link' => $this->_helper->url("index", "forwarders"),
                ),
            array(
                'title' => 'Auto Responders',
                'description' => 'Example module with UI samples',
                'class' => 'auto-responders',
                // 'action' => 'default-Addresses'
                'link' => $this->_helper->url("index", "auto-responders"),
                ),

            ); 

        $client = pm_Session::getClient();

        if ($client->isAdmin()) {

            array_push($smallTools, array(
                'title' => 'Change Domain',
                'description' => 'Modules installed in the Panel',
                'class' => 'sb-suspend',
                'link' => pm_Context::getBaseUrl(),
                ));



            array_push($smallTools, array(

                'title' => 'Account Types',
                'description' => 'Modules installed in the Panel',
                'class' => 'sb-suspend',
                'action' => 'set-Types',
                ));

        }

        return $smallTools;
    }

    /*
    *************** 
    * Method for creating link button with params and image
    ***************
    */
    public function addButton($controller, $action, $imgName, $params='', $confirm=false, $class = '', $id = '')
    {
        $href = $this->_helper->url($action, $controller, '', $params);
        $onclick = ($confirm ? 'onclick="return confirm(\'Are you sure you want to delete this auto responder?\');"' : '');
        return sprintf("<a style=\"text-decoration:none\" href=%s $onclick class=\"%s\" id=%s>%s</a>", $href, $class, $id, $imgName);
    }

}
?>