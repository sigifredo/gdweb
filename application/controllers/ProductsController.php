<?php

class ProductsController extends Zend_Controller_Action
{

    private $session = null;
    private $auth = null;
    private $sql = null;

    /**
     * \brief Contruye las variables de la clase
     *
     */
    public function init()
    {
        $this->sql = new Application_Model_SQL();
        $this->session = new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        $this->view->proyect = $this->sql->listNonFreeProyects();
    }

    /**
     * \brief action para crear proyecto
     *
     */
    public function createAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        $form = new CreateProyectForm();
        $form->setAction($this->view->url(array("controller" => "products", "action" => "create")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Datos del proyecto.</span>";
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
            $image = GD3W_PATH."/img/proy/".$form->image->getFileName(null, false);
        else
            $image = '';

        $this->sql->insertProyect($values['name'], $values['description'], $values['cc_client'], $values['type'], $image);

        $this->_forward('list', 'products');
    }

    /**
     * \brief action para borrar proyecto.
     *
     */
    public function deleteAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        if(!$this->_hasParam('p'))
        {
            $this->_helper->redirector('list', 'products');
            return;
        }

        $this->sql->deleteProyect($this->getRequest()->getParam('p'));

        $this->_forward('list', 'products');
    }

    public function listAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        $this->view->products = $this->sql->listProyects();
    }

    public function updateAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        if(!$this->_hasParam('p'))
            $this->_helper->redirector('list', 'products');

        $iIdProyect = $this->getRequest()->getParam('p');
        $form = new UpdateProyectForm();
        $form->setAction($this->view->url(array("controller" => "products", "action" => "update")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos del proyecto.</span>";
            $datos = $this->sql->proyect($iIdProyect);

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

            if(isset($values['image']))
                $sImage = GD3W_PATH."/img/proy/".$form->image->getFileName(null,false);
            else
                $sImage = '';

            $this->sql->updateProyect($iIdProyect, $values['name'], $values['description'], $values['cc_client'], $values['type'], $sImage);

            $this->_forward('list', 'products');
        }
    }

}
