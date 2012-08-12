<?php

class ServicesController extends Zend_Controller_Action
{
    private $session = null;
    private $auth = null;

    public function init()
    {
        $this->session = new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        $this->view->headTitle("Nuestros servicios");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/info.css');

        $tbService = new TbService();
        $this->view->services = $tbService->fetchAll();
    }

    public function createAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }

        $this->view->headTitle("Crear servicio");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/forms.css');

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

        if($values['image'] == '')
            unset($values['image']);
        else
            $values['image'] = GD3W_PATH."/img/serv/".$form->image->getFileName(null, false);

        $values['cc_owner'] = $this->session->user;

        $tbService = new TbService();
        $tbService->insert($values);

        $this->_forward('list', 'services');
    }

    /**
     * \brief action para borrar servicio.
     *
     */
    public function deleteAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        if(!$this->_hasParam('s'))
        {
            $this->_helper->redirector('list', 'service');
            return;
        }

        $tbService = new TbService();
        $tbService->delete("id=".$this->getRequest()->getParam('s'));

        $this->_forward('list', 'services');
    }

    public function listAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        $this->view->headTitle("Servicios");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/list.css');

        $tbService = new TbService();
        $this->view->services = $tbService->select()->query()->fetchAll();
    }

    public function updateAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        if(!$this->_hasParam('s'))
            $this->_helper->redirector('list', 'services');

        $this->view->headTitle("Actualizar servicio");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/forms.css');

        $iIdService = $this->getRequest()->getParam('s');
        $form = new UpdateServiceForm();
        $form->setAction($this->view->url(array("controller" => "services", "action" => "update")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos del servicio.</span>";

            $tbService = new TbService();
            $datos = $tbService->find($this->getRequest()->getParam('s'));
            $datos = $datos[0]->toArray();
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

            if($values['image'] == '')
                unset($values['image']);
            else
                $values['image'] = GD3W_PATH."/img/usr/".$form->image->getFileName(null,false);

            $tbService = new TbService();
            $datos = $tbService->update($values, "id=".$this->getRequest()->getParam('s'));

            $this->_forward('list', 'services');
        }
    }
}
