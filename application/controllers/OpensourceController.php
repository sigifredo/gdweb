<?php

class OpensourceController extends Zend_Controller_Action
{

    private $session = null;

    private $auth = null;

    private $sql=null;


    /**
      * \brief Contruye las variables de la clase
      *
      * @return N/A
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
        $this->view->proyect = $this->sql->listOpenSourceProyects();
        return;
    }

}

