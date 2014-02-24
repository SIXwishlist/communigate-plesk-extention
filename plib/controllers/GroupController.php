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
        // The default action will redirect to the list-Group action
        $this->_forward('list-Group');    
    }

    /*
    *************** 
    * this action is for listing forwadrers and operations
    ***************
    */
    public function listGroupAction()
    {
        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools; 
        
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

        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools;

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
    * this action is for deleting group
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


    public function renameAction()
    {
        $this->view->text = 'Rename Group';

        $form = new Modules_Communigate_Form_RenameGroup();
        $group = $this->_getParam('group');

        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools;

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process($group);
            $this->_status->addMessage('info', 'Group succesfully renamed!');
            $this->_helper->json(array('redirect' => $this->_helper->url("list-Group", "group")));
        };
        $this->view->form = $form;
    }

    public function settingsAction()
    {
        $this->view->text = 'Group Settings';

        $form = new Modules_Communigate_Form_GroupSettings();
        
        $group = $this->_getParam('group');

        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools;

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $form->process($group);
            $this->_status->addMessage('info', 'Group settings succesfully changed!');
            $this->_helper->json(array('redirect' => $this->_helper->url("list-Group", "group")));
        };
        $this->view->form = $form; 
    }

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

        $smallTools = $this->_getSmallTools();

        $this->view->smallTools = $smallTools; 

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