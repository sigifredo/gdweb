<?php

class ProductsController extends Zend_Controller_Action
{

    private $session = null;
    private $auth = null;
    private $sql=null;


    /**
     * \brief Contruye las variables de la clase
     *
     */
    public function init()
    {
        $this->sql = new Application_Model_SQL();
        $this->session=new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        $this->view->proyect = $this->sql->listProyects();
        return;
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
            echo "<h4 id='infpro'>Datos De Proyecto</h4>";
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
        {
            $image = APPLICATION_PATH."/../public/img/proy/".$form->image->getFileName(null,false);

        }
        else
        {
            $image = '';
        }

        //  $this->sql->insertProyect($values['name],$values['description'],$image,$values['client'],$values['type']);

        $this->_helper->redirector('index', 'index');
        return;
    }

}
