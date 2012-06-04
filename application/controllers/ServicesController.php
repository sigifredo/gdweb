<?php

class ServicesController extends Zend_Controller_Action
{
    private $session = null;
    private $auth = null;
    private $sql = null;

    public function init()
    {
        $this->sql = new Application_Model_SQL();
        $this->session = new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        $this->view->headTitle("Nuestros servicios");
    }

    public function createAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        $form = new CreateServiceForm();
        $form->setAction($this->view->url(array("controller" => "services", "action" => "create")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Datos del servicio.</span>";
            echo $form;
            return;
        }
        if(!$form->isValid($this->_getAllParams()))
        {
            echo $form;
            return;
        }
        $values = $form->getValues();

        $this->sql->insertService($values['name'], $values['description'], $this->session->user);

        $this->_forward('list', 'products');
    }

    public function listAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');
        $this->view->services = $this->sql->listServices();
    }

    public function updateAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        if(!$this->_hasParam('s'))
            $this->_helper->redirector('list', 'services');

        $iIdService = $this->getRequest()->getParam('s');
        $form = new UpdateServiceForm();
        $form->setAction($this->view->url(array("controller" => "services", "action" => "update")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos del proyecto.</span>";
            $datos = $this->sql->service($iIdService);

            echo $form->populate($datos);
        }
        else
        {
            if(!$form->isValid($this->_getAllParams()))
            {
                echo $form;
                return;
            }

            $values = $form->getValues();

            $this->sql->updateService($iIdService, $values['name'], $values['description']);

            $this->_forward('list', 'services');
        }
    }
}
