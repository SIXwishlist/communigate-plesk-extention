<?php
/*
*************** 
 * This is the controller for the User Levele Filtering
***************
*/ 
class FilterController extends pm_Controller_Action
{

    public function init()
    {
        parent::init();

        // Init title for all actions
        $this->view->pageTitle = 'Unified Messaging';

        $mailingList = $this->_helper->url("index", "list");
        $accounts = $this->_helper->url("list-Accounts", "index");
        $mailArchiving = $this->_helper->url("email-Archiving", "index");
        $group = $this->_helper->url("index", "group");
        $remotePop = $this->_helper->url("index", "remote-Pop");
        $filter = $this->_helper->url("index", "filter");

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
            array(
                'title' => 'Email Archiving',
                'link' => $mailArchiving,
                ),
            array(
                'title' => 'Group',
                'link' => $group,
                ),
            array(
                'title' => 'Remote POP',
                'link' => $remotePop,
                ),
            array(
                'title' => 'User Level Filtering',
                'link' => $filter,
                ),
            );
    }

    /*
    *************** 
    * this action is for displaying all the accounts
    ***************
    */
    public function indexAction() 
    {
        $this->view->heading = 'Manage Filters';
        
        $this->view->text = "In this area, you can manage filters for each user. Each user filter is processed after the main account filters.";

        $list = $this->_getListAccounts();
        
        $this->view->list = $list;
    }

    /*
    *************** 
    * this action is for displaying filters and managing them
    ***************
    */
    public function manageFiltersAction()
    {
        $account = $this->_getParam('account');

        $this->view->heading = "Edit Filters for: $account";
        
        $this->view->text = 'In this area you can manage filters for your main account.';

        $this->view->btnText = 'Create Filter';

        $this->view->button = "<a href=".$this->_helper->url('create', 'filter', '', array('account' => $account))." class=\"btn\">Create а New Filter</a>";

        $list = $this->_getListFilters($account);
        
        $this->view->list = $list;
    }

    /*
    *************** 
    * this action is for adding rules to account
    ***************
    */
    public function createAction()
    {
        $account = $this->_getParam('account');
        
        $this->view->heading = "Create a New Filter for $account";

        $this->view->text = 'Please create a filter below. You can add multiple rules to match subjects, addresses, or other parts of the message. You can then add multiple actions to take on a message such as to deliver the message to a different address and then discard it.';

        $form = new Modules_Communigate_Form_CreateFilter(); 

        $account = $this->_getParam('account');

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

            $filterObj = new Modules_Communigate_Custom_Filters();
            $filterObj->createFilter($_POST, $account);

            // $form->process();
            $this->_status->addMessage('info', 'Filter succesfully added!');
            // $account = $this->_getParam('account');
            $params = array('account' => $account);
            $this->_helper->json(array('redirect' => $this->_helper->url("manage-Filters", "filter",'', $params)));
        };
        $this->view->form = $form;
    }

    /*
    *************** 
    * this action is for deleting Filters
    ***************
    */
    public function deleteAction()
    {
        $filter = $this->_getParam('filter');
        $account = $this->_getParam('account');
        $filterObj = new Modules_Communigate_Custom_Filters();
        $filterObj->deleteFilter($filter, $account);

        $this->_status->addMessage('info', 'Filter succesfully deleted!');
        $params = array('account' => $account);
        $this->_helper->redirector("manage-Filters", "filter",'', $params);
    }

    /*
    *************** 
    * this action is for updating Filters
    ***************
    */
    public function updateAction()
    {
        $account = $this->_getParam('account');

        $filter = $this->_getParam('filter');

        $this->view->heading = "Edit a Filter for $account";

        $this->view->text = 'Please edit the filter below. You can add multiple rules to match subjects, addresses, or other parts of the message. You can then add multiple actions to take on a message such as to deliver the message to a different address and then discard it.';

        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $filters = $cli->GetAccountRules($account);

        foreach ($filters as $filterName) {
            if ($filterName[1] == $filter) {
                $filterInfo = $filterName;
            }
        }

        $this->view->filter = $filterInfo;

        $form = new Modules_Communigate_Form_CreateFilter();

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

            $filterObj = new Modules_Communigate_Custom_Filters();
            $filterObj->deleteFilter($filter, $account);
            $filterObj->createFilter($_POST, $account);

            $this->_status->addMessage('info', 'Filter succesfully updated!');
            $params = array('account' => $account);
            $this->_helper->json(array('redirect' => $this->_helper->url("manage-Filters", "filter",'', $params)));
        };

        $this->view->form = $form;
    }

    /*
    *************** 
    * helper method to create the list of Accounts
    ***************
    */
    private function _getListAccounts()
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $accounts = Modules_Communigate_Custom_Filters::getAllAccounts($domain);

        $data = array();

        foreach ($accounts as $account) {
          $data[] = array(
            'column-1' => $account,
            'column-2' =>
            $this->addButton('filter', 'manage-Filters', 'Manage Filters',
                array('account' => $account))
            );
      }

      $list = new pm_View_List_Simple($this->view, $this->_request);
      $list->setData($data);
      $list->setColumns(array(
        'column-1' => array(
            'title' => 'Account',
            'noEscape' => true,
            ),
        'column-2' => array(
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
    * helper method to create the list of filters
    ***************
    */
    private function _getListFilters($account)
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $filters = Modules_Communigate_Custom_Filters::getFilters($account);

        $data = array();

        foreach ($filters as $filter) {
          $data[] = array(
            'column-1' => $filter,
            'column-2' =>
            $this->addButton('filter', 'update', 'Edit',
                array('filter' => $filter, 'account' => $account)) . '&nbsp&nbsp&nbsp&nbsp'.
            $this->addButton('filter', 'delete', 'Delete',
                array('filter' => $filter, 'account' => $account), true)
            );
      }

      $list = new pm_View_List_Simple($this->view, $this->_request);
      $list->setData($data);
      $list->setColumns(array(
        'column-1' => array(
            'title' => 'Current Filters',
            'noEscape' => true,
            ),
        'column-2' => array(
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
    * Method for creating link button with params and image
    ***************
    */
    public function addButton($controller, $action, $imgName, $params='', $class = false, $id = '')
    {
        $href = $this->_helper->url($action, $controller, '', $params);
        if ($class == true) {
          $onclick = 'onclick="return confirm(\'Are you sure you want to delete this forwarder?\');"';
      } 
      $onclick = '';
      return sprintf("<a style=\"text-decoration:none\" href=%s $onclick id=%s>%s</a>", $href, $id, $imgName);
    }

}
?>