<?php
/*
*************** 
 * This is the controller for the domains opperations
***************
*/ 
class GroupController extends pm_Controller_Action
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

    public function indexAction()
    {
        // The default action will redirect to the list-Group action
        $this->_forward('list-Group');    
    }

    /*
    *************** 
    * this action is for listing groups and operations
    ***************
    */
    public function listGroupAction()
    {
         
        $list = $this->_getListGroups();

        $button = "<a href=".$this->_helper->url("create", "group")." class=\"btn\">Create Group</a>";

        $this->view->button = $button;

        $this->view->text = "Groups";

        // List object for pm_View_Helper_RenderList
        $this->view->list = $list;
    }

    /*
    *************** 
    * this action is for creating new group
    ***************
    */
    public function createAction()
    {
        $this->view->text = 'Add a New Group';

        $form = new Modules_Communigate_Form_CreateGroup(); 

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process();
            $this->_status->addMessage('info', 'Group succesfully created!');
            $this->_helper->json(array('redirect' => $this->_helper->url("list-Group", "group")));
        };
        $this->view->form = $form;
    }

    /*
    *************** 
    * this action is for deleting group
    ***************
    */
    public function deleteAction()
    {
        $group = $this->_getParam('group');
        Modules_Communigate_Custom_Groups::deleteGroup($group);

        $this->_status->addMessage('info', "Group $group was succesfully deleted!");
        $this->_helper->redirector('list-Group', 'group');
    }

    /*
    *************** 
    * this action is for deleting a group member
    ***************
    */
    public function deleteMemberAction()
    {
        $group = $this->_getParam('group');
        $member = $this->_getParam('member');
        Modules_Communigate_Custom_Groups::removeMember($member, $group);
        $this->_status->addMessage('info', "Group member $member was succesfully deleted!");
        $params = array('group' => $group);
        $this->_helper->redirector("group-Members", "group",'', $params);
    }

    /*
    *************** 
    * this action is for renaming a group
    ***************
    */
    public function renameAction()
    {
        $this->view->text = 'Rename Group';

        $form = new Modules_Communigate_Form_RenameGroup();
        $group = $this->_getParam('group');

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process($group);
            $this->_status->addMessage('info', 'Group succesfully renamed!');
            $this->_helper->json(array('redirect' => $this->_helper->url("list-Group", "group")));
        };
        $this->view->form = $form;
    }
    
    /*
    *************** 
    * this action is for changing the settings of a group
    ***************
    */    public function settingsAction()
    {
        $this->view->text = 'Group Settings';

        $form = new Modules_Communigate_Form_GroupSettings();
        
        $group = $this->_getParam('group');

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process($group);
            $this->_status->addMessage('info', 'Group settings succesfully changed!');
            $this->_helper->json(array('redirect' => $this->_helper->url("list-Group", "group")));
        };
        $this->view->form = $form; 
    }

    /*
    *************** 
    * this action is for viewing group members
    * and adding new ones
    ***************
    */
    public function groupMembersAction()
    {
        
		$form = new Modules_Communigate_Form_AddMember();
		$group = $this->_getParam('group');
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process($group);
            $params = array('group' => $group);
            $this->_status->addMessage('info', 'Member succesfully added!');
            $this->_helper->json(array('redirect' => $this->_helper->url("group-Members", "group",'', $params)));
        };
        $this->view->form = $form;

        $list = $this->_getListMembers($group);

        $button = "<a href=".$this->_helper->url("create", "group")." class=\"btn\">Add Members</a>";

        $this->view->button = $button;

        $this->view->heading = "Members of group: $group";

        $this->view->text = "Group Members";

        // List object for pm_View_Helper_RenderList
        $this->view->list = $list;
    }

    /*
    *************** 
    * helper method to create the list of groups
    ***************
    */
    private function _getListGroups()
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $group = new Modules_Communigate_Custom_Groups($domain);
        $groupData = $group->getData();
 
        $data = array();

        foreach ($groupData as $group) {
          $data[] = array(
            'column-1' => $group['group'],
            'column-2' =>
            $this->addButton('group', 'delete', 'Delete',
                array('group' => $group['group']), true) . '&nbsp&nbsp&nbsp&nbsp'.
            $this->addButton('group', 'rename', 'Rename',
                 array('group' => $group['group']))  . '&nbsp&nbsp&nbsp&nbsp'.
            $this->addButton('group', 'settings', 'Settings',
                 array('group' => $group['group'])) .  '&nbsp&nbsp&nbsp&nbsp'.
            $this->addButton('group', 'group-Members', 'Group Members',
                 array('group' => $group['group'])) ,
            );
      }

      $list = new pm_View_List_Simple($this->view, $this->_request);
      $list->setData($data);
      $list->setColumns(array(
        'column-1' => array(
            'title' => 'Group',
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
    * helper method to create the list of group members
    ***************
    */
    private function _getListMembers($groupe)
    {
        $request = new Zend_Controller_Request_Http();
        $domain = $request->getCookie('domain');
        $groupParam = $this->_getParam('group');
        $group = new Modules_Communigate_Custom_Groups($domain);
        $groupData = $group->getDataForGroupMembers($groupe);
        $data = array();

        for ($i=0; $i < count($groupData) ; $i++) { 
        	$data[] = array(
        		'column-1' => $groupData[$i],
        		'column-2' =>
        		$this->addButton('group', 'delete-Member', 'Delete',
        			array('member' => $i, 'group' => $groupParam), true),
        		);
        }

      $list = new pm_View_List_Simple($this->view, $this->_request);
      $list->setData($data);
      $list->setColumns(array(
        'column-1' => array(
            'title' => 'Members',
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