<?php

class ProductsController extends Zend_Controller_Action
{

    private $session = null;
    private $auth = null;

    /**
     * \brief Contruye las variables de la clase
     *
     */
    public function init()
    {
        $this->session = new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        $this->view->headTitle("Nuestros productos");

        $tbProyect = new TbProyect();
        $this->view->proyects = $tbProyect->fetchAll();
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
            $values['image'] = GD3W_PATH."/img/proy/".$form->image->getFileName(null, false);
        else
            $values['image'] = '';

        $values['id_proyecttype'] = $values['proyecttype'];
        unset($values['proyecttype']);

        $tbProyect = new TbProyect();
        $tbProyect->insert($values);

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

        $tbProyect = new TbProyect();
        $tbProyect->delete("id=".$this->getRequest()->getParam('p'));

        $this->_forward('list', 'products');
    }

    public function listAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        $tbProyect = new TbProyect();
        $this->view->proyects = $tbProyect->fetchAll();
    }

    public function updateAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        if(!$this->_hasParam('p'))
            $this->_helper->redirector('list', 'products');

        $form = new UpdateProyectForm();
        $form->setAction($this->view->url(array("controller" => "products", "action" => "update")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos del proyecto.</span>";

            $tbProyect = new TbProyect();
            $datos = $tbProyect->find($this->getRequest()->getParam('p'))[0]->toArray();
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
                $values['image'] = GD3W_PATH."/img/proy/".$form->image->getFileName(null,false);

            $tbProyect = new TbProyect();
            $tbProyect->update($values, "id=".$this->getRequest()->getParam('p'));

            $this->_forward('list', 'products');
        }
    }

}
