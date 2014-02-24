<?php 
class ListController extends pm_Controller_Action
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
            );
    }

    public function indexAction()
    {
        // Redirrecting to the read action
        $this->_forward('read');     
    }

    /*
    *************** 
    * Action for displaying all the maingin lists
    ***************
    */
    public function readAction()
    {
        $list = $this->_listMailingLists();

        // List object for pm_View_Helper_RenderList
        $this->view->list = $list;
        $this->view->button = "<a href=" . $this->_helper->url("create", "list") .
        " class=\"btn\">Create Mailing List</a>";
    }

    /*
    *************** 
    * Action for creating a mailing list
    ***************
    */
    public function createAction()
    {
        $this->view->message = 'Fill this form with your account credentials to subscribe';
        
        $form = new Modules_Communigate_Form_MailingList(); 

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();
            $this->_status->addMessage('info', 'You have successfuly created a mailing list!');
            $this->_helper->json(array('redirect' => $this->_helper->url("index", "list")));
        }

        $this->view->form = $form;
    }

    /*
    *************** 
    * Action for updating a mailing list
    ***************
    */
    public function updateAction()
    {
        $this->view->message = 'Update the mailing list settings';

        $list = Zend_Controller_Front::getInstance()->getRequest()->getParam( 'list', null );
        $url = $this->_helper->url("delete", "list", '', array('list' => $list));

        $form = new Modules_Communigate_Form_UpdateList(array('url'=>$url)); 

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();
            $this->_status->addMessage('info', 'You have successfuly updated the mailing list!');
            $this->_helper->json(array('redirect' => $this->_helper->url("read", "list")));

        }
        $onclick = 'onclick="return confirm(\'Are you sure you want to delete this mailing list?\');"';
        $this->view->button =  '<a href="'. $url .'"'. $onclick .'>Delete list</a>';
        $this->view->form = $form;
    }

    /*
    *************** 
    * Action for deleting a mailing list
    ***************
    */
    public function deleteAction()
    {
        // get info
        $list = Zend_Controller_Front::getInstance()->getRequest()->getParam('list');
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        // delete mailing list
        $cli = Modules_Communigate_Custom_Accounts::ConnectToCG();
        $cli->DeleteList("$list@$domain");
        // redirect
        $this->_helper->redirector('index', 'list');
    }

    /*
    *************** 
    * Action for subscribing to a mailing list
    ***************
    */
    public function subscribeAction()
    {
        $list = $this->_getParam('list');

        $this->view->message = 'Fill this form with your account credentials to subscribe';

        $form = new Modules_Communigate_Form_Identify(); 

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {            
            // get info
            $acc = $form->getValue("accountName");
            $request = new Zend_Controller_Request_Http();
            $domain = $request->getCookie('domain');
            // subscribe
            Modules_Communigate_Custom_Lists::subscribe($acc, $domain, $list);
            // redirect
            $this->_status->addMessage('info', 'You have successfuly subscribed!');
            $this->_helper->json(array('redirect' => $this->_helper->url('read', "list")));            
        }
        $this->view->form = $form;   
    }

    /*
    *************** 
    * Action for unsubscribing to a mailing list
    ***************
    */
    public function unsubscribeAction()
    {
        $list = $this->_getParam('list');

        $this->view->message = 'Fill this form with your account credentials to subscribe';

        $form = new Modules_Communigate_Form_Unsubscribe(); 

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            // get info   
            $acc = $form->getValue("accountName");
            $request = new Zend_Controller_Request_Http();
            $domain = $request->getCookie('domain');
            // unsubscribe
            Modules_Communigate_Custom_Lists::unsubscribe($acc, $domain, $list);
            // redirect
            $this->_status->addMessage('info', 'You have successfuly subscribed!');
            $this->_helper->json(array('redirect' => $this->_helper->url('read', "list")));            
        }
        $this->view->form = $form;
    }

    /*
    *************** 
    * Action for verifying account before updating
    ***************
    */
    public function identifyAction()
    {
        $form = new Modules_Communigate_Form_Identify();

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $list = Zend_Controller_Front::getInstance()->getRequest()->getParam('list');
            $this->_helper->json(array('redirect' => $this->_helper->url("update", "list", '', array('list' => $list))));
        }
        $this->view->form = $form;
    }

    public function listDataAction()
    {
        $list = $this->_getListAccounts();

        // Json data from pm_View_List_Simple
        $this->_helper->json($list->fetchData());
    }

    private function _listMailingLists()
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $mailingLists = Modules_Communigate_Custom_Lists::GetMailingLists($domain);      

        foreach ($mailingLists as $listName => $details) {
            $data[] = array(
                'column-1' => "<a href=" . $this->_helper->url("identify", "list", '',
                    array('account' => $details['owner'], 'list' => $listName)) . ">" . $listName . "</a>",
                'column-2' => $details['owner'],
                'column-3' => $details['subscribers'],
                'column-4' => "<a href=" . $this->_helper->url("subscribe", "list", '',
                    array('list' => $listName)) . ">Subscribe</a>" ,
                'column-5' => "<a href=" . $this->_helper->url("unsubscribe", "list", '',
                    array('list' => $listName)) . ">Unsubscribe</a>",
                );

            if ($data[0]['column-2'] === '') {
                $data = array();
            }
        }

        $list = new pm_View_List_Simple($this->view, $this->_request);
        $list->setData($data);
        $list->setColumns(array(
            'column-1' => array(
                'title' => 'Mailing List',
                'noEscape' => true,
                ),
            'column-2' => array(
                'title' => 'Created By',
                'noEscape' => true,
                ),
            'column-3' => array(
                'title' => 'Subscribers',
                'noEscape' => true,
                ),
            'column-4' => array(
                'title' => '',
                'noEscape' => true,
                ),
            'column-5' => array(
                'title' => '',
                'noEscape' => true,
                ),
            ));
        // Take into account listDataAction corresponds to the URL /list-data/
        $list->setDataUrl(array('action' => 'list-data'));

        return $list;
    }
}

?>