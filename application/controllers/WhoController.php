<?php

class WhoController extends Zend_Controller_Action
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
        $this->view->headTitle("¿Quiénes somos?");

        $tbInfo = new TbInfo();
        $this->view->info = $tbInfo->fetchAll();
    }

    /**
     * \brief action para listar información de la empresa
     *
     */
    public function listAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'who');
        else
        {
            $tbInfo = new TbInfo();
            $this->view->info = $tbInfo->select()->query()->fetchAll();
        }
    }

    /**
     * \brief action para crear información de la empresa
     *
     */
    public function createAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'who');
            return;
        }

        $this->view->headTitle("Guardar información");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/forms.css');

        $form = new CreateInfoForm();
        $form->setAction($this->view->url(array("controller" => "who", "action" => "create")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Datos.</span>";
            echo $form;
            return;
        }
        if(!$form->isValid($this->_getAllParams()))
        {
            echo $form;
            return;
        }

        $values = $form->getValues();

        if(isset($values['image']))
            $values['image'] = GD3W_PATH."/img/inf/".$form->image->getFileName(null,false);
        else
            $valies['image'] = '';

        $tbInfo = new TbInfo();
        $tbInfo->insert($values);

        $this->_helper->redirector('list', 'who');
    }


    /**
     * \brief action para modificar información de la empresa
     *
     */
    public function updateAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'who');
            return;
        }

        if(!$this->_hasParam('info'))
        {
            $this->_helper->redirector('list', 'who');
            return;
        }

        $form = new UpdateInfoForm();
        $form->setAction($this->view->url(array("controller" => "who", "action" => "update")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos.</span>";

            $tbInfo = new TbInfo();
            $datos = $tbInfo->find($this->getRequest()->getParam('info'));
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
                $values['image'] = GD3W_PATH."/img/inf/".$form->image->getFileName(null, false);

            $tbInfo = new TbInfo();
            $datos = $tbInfo->update($values, "id=".$this->getRequest()->getParam('info'));

            $this->_helper->redirector('list', 'who');
        }
    }


    /**
     * \brief action para borrar información de la empresa
     *
     */
    public function deleteAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'who');
            return;
        }
        if(!$this->_hasParam('info'))
        {
            $this->_helper->redirector('list', 'who');
            return;
        }

        $tbInfo = new TbInfo();
        $tbInfo->delete("id=".$this->getRequest()->getParam('info'));

        $this->_helper->redirector('list', 'who');
    }

}
