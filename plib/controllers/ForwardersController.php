<?php
/*
*************** 
 * This is the controller for the domains opperations
***************
*/ 
class ForwardersController extends pm_Controller_Action
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
        // The default action will redirect to the list-Forwarder action
        $this->_forward('list-Forwarders');    
    }

    /*
    *************** 
    * this action is for listing forwadrers and operations
    ***************
    */
    public function listForwardersAction()
    {
        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools; 
        
        $list = $this->_getListForwarders();

        $button = "<a href=".$this->_helper->url("create", "forwarders")." class=\"btn\">Add Forwarder</a>";

        $this->view->button = $button;

        $this->view->text = "Email Account Forwarders";

        // List object for pm_View_Helper_RenderList
        $this->view->list = $list;
    }

    /*
    *************** 
    * this action is for creating new forwadrer
    ***************
    */
    public function createAction()
    {
        $this->view->text = 'Add a New Forwarder';

        $form = new Modules_Communigate_Form_CreateForwarder(); 

        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools;

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();
            $this->_status->addMessage('info', 'Forwarder succesfully created!');
            $this->_helper->json(array('redirect' => $this->_helper->url("list-Forwarders", "forwarders")));
        };
        $this->view->form = $form;
    }

    /*
    *************** 
    * this action is for deleting new forwadrer
    ***************
    */
    public function deleteAction()
    {
        $forwarder = $this->_getParam('forwarder');
        Modules_Communigate_Custom_Forwarders::deleteForwarder($forwarder);

        $this->_status->addMessage('info', "Forwarder $forwarder was succesfully deleted!");
        $this->_helper->redirector('list-Forwarders', 'forwarders');
    }

    /*
    *************** 
    * helper method to create the list of forwarders
    ***************
    */
    private function _getListForwarders()
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $forwarders = new Modules_Communigate_Custom_Forwarders($domain);
        $forwardersData = $forwarders->getForwarders();

        $data = array();

        foreach ($forwardersData as $key => $value) {
          $data[] = array(
            'column-1' => $key,
            'column-2' => $value,
            'column-3' =>  $this->addButton('forwarders', 'delete', 'Delete',
                array('forwarder' => $key)),
            );
      }

      $list = new pm_View_List_Simple($this->view, $this->_request);
      $list->setData($data);
      $list->setColumns(array(
        'column-1' => array(
            'title' => 'Address',
            'noEscape' => true,
            ),
        'column-2' => array(
            'title' => 'Forward To',
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
    public function addButton($controller, $action, $imgName, $params='', $class = '', $id = '')
    {
        $href = $this->_helper->url($action, $controller, '', $params);
        $onclick = 'onclick="return confirm(\'Are you sure you want to delete this forwarder?\');"';
        return sprintf("<a style=\"text-decoration:none\" href=%s $onclick class=\"%s\" id=%s>%s</a>", $href, $class, $id, $imgName);
    }

    /*
    *************** 
    * recursive search to find redirect rule for accounts
    ***************
    */
    public function recursive_array_search($needle,$haystack) 
    {
        foreach($haystack as $key=>$value) {
            $current_key=$key;
            if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }
}
?>