<?php
/*
*************** 
 * This is the controller for the domains opperations
***************
*/ 
class RemotePopController extends pm_Controller_Action
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
            );
    }

    public function indexAction()
    {
        $form = new Modules_Communigate_Form_SelectAccount();

        $account = $this->_getParam('account');

        if (isset($account)) {

            $button = "<a href=".$this->_helper->url("create", "remote-Pop" ,'' , array('account' => $account))." class=\"btn\">Add Remote POP</a>";

            $this->view->button = $button;
            $this->view->text = "Remote POP for account: $account";
            $list = $this->_getListPops($account);
            $this->view->list = $list;
        }

        $this->view->form = $form;
    }

    /*
    *************** 
    * this action is for adding remote pop to account
    ***************
    */
    public function createAction()
    {
        $this->view->text = 'Add Remote POP';

        $form = new Modules_Communigate_Form_AddPop(); 

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();
            $this->_status->addMessage('info', 'Remote POP succesfully added!');
            $account = $this->_getParam('account');
            $params = array('account' => $account);
            $this->_helper->json(array('redirect' => $this->_helper->url("index", "remote-Pop",'', $params)));
        };
        $this->view->form = $form;
    }

    /*
    *************** 
    * this action is for deleting POPS
    ***************
    */
    public function deleteAction()
    {
        $name = $this->_getParam('name');
        $account = $this->_getParam('account');
        $pop = new Modules_Communigate_Custom_RemotePop;
        $pop->deleteRemotePop($name, $account);

        $this->_status->addMessage('info', "Remote POP $name was succesfully deleted!");
        $params = array('account' => $account);
        $this->_helper->redirector('index', 'remote-Pop','', $params);
    }

    /**
     * Action for viewing and editing POPS
     */
    public function viewEditAction()
    {
        $this->view->text = 'View/Edit Remote POP';

        $form = new Modules_Communigate_Form_AddPop(); 

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();
            $this->_status->addMessage('info', 'Remote POP succesfully added!');
            $account = $this->_getParam('account');
            $params = array('account' => $account);
            $this->_helper->json(array('redirect' => $this->_helper->url("index", "remote-Pop",'', $params)));
        };
        $this->view->form = $form;
    
    }

    /*
    *************** 
    * helper method to create the list of POPS
    ***************
    */
    private function _getListPops($account)
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $pop = new Modules_Communigate_Custom_RemotePop;
        $popData = $pop->getDataForPops($account);
        $data = array();

        foreach ($popData as $pop) {
          $data[] = array(
            'column-1' => $pop['popName'],
            'column-2' =>
            $this->addButton('remote-Pop', 'view-Edit', 'View/Edit',
                array('name' => $pop['popName'] , 'account' => $pop['account']), true) . '&nbsp&nbsp&nbsp&nbsp'.
            $this->addButton('remote-Pop', 'delete', 'Delete',
                 array('name' => $pop['popName'] , 'account' => $pop['account']))  . '&nbsp&nbsp&nbsp&nbsp'
            );
      }

      $list = new pm_View_List_Simple($this->view, $this->_request);
      $list->setData($data);
      $list->setColumns(array(
        'column-1' => array(
            'title' => 'Remote POP',
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