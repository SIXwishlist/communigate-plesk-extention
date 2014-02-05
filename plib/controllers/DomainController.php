<?php
/*
*************** 
 * This is the controller for the domains opperations
***************
*/ 
class DomainController extends pm_Controller_Action
{

    public function init()
    {
        parent::init();

        // Init title for all actions
        $this->view->pageTitle = 'Unified Messaging';
    }

    public function indexAction()
    {
        // The default action will redirect to the create action
        $this->_forward('create');    
    }

    /*
    *************** 
    * this action is for creating a new domain
    ***************
    */
    public function createAction()
    {

        $this->view->test = 'Please fill the form to create domain';

        $form = new Modules_Communigate_Form_CreateDomain(); 

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

            $form->process();

            $domain = $form->getValue('domainName');

            setcookie('domain', $domain, time()+3600, '/modules/communigate');

            $this->_status->addMessage('info', 'Domain succesfully created!');
            $this->_helper->json(array('redirect' => $this->_helper->url("list-Accounts", "index")));
        };
         $this->view->form = $form;
    }

}
?>