<?php

class WhoController extends Zend_Controller_Action
{
    private $session = null;

    private $auth = null;

    private $sql=null;

    public function init()
    {
        $this->sql = new Application_Model_SQL();
        $this->session=new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        $this->view->headTitle("¿Quiénes somos?");

        $info = $this->sql->listInformation(APPLICATION_PATH."/../public/pg/img/inf");
        echo "<div id='information'>";

        if(!count($info))
            echo "No hay información.";
        foreach($info as $line)
        {
            echo "<article>";
            echo "<header class='title'>".$line['title']."</header>";
            //echo "<img src='".$this->view->baseUrl()."/pg/img/inf/".$line['image']."'/>";
            echo "<p>".$line['description']."</p>";
            echo "<div class='clear'></div></article>";
        }
        echo "</div>";
    }

    /**
     * \brief action para listar información de la empresa
     *
     */
    public function listInfoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'who');
        else
            $this->view->info = $this->sql->listInformation();
    }

    /**
     * \brief action para crear información de la empresa
     *
     */
    public function createInfoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'who');
            return;
        }
        $form = new CreateInfoForm();
        $form->setAction($this->view->url(array("controller" => "who", "action" => "create-info")))
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
            $image = GD3W_PATH."/img/inf/".$form->image->getFileName(null,false);
        else
            $image = '';

        $this->sql->insertInfo($values['title'],$values['description'],$image);

        $this->_helper->redirector('index', 'who');
    }


    /**
     * \brief action para modificar información de la empresa
     *
     */
    public function updateInfoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'who');
            return;
        }

        if(!$this->_hasParam('info'))
        {
            $this->_helper->redirector('list-info', 'who');
            return;
        }

        $iIdInfo = $this->getRequest()->getParam('info');
        $form = new UpdateInfoForm();
        $form->setAction($this->view->url(array("controller" => "who", "action" => "update-info")))
             ->setMethod('post');

        $datos = $this->sql->listInformation();

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos.</span>";
            foreach($datos as $line)
                if($line['id'] == $iIdInfo)
                    echo $form->populate($line);

            return;
        }
        if(!$form->isValid($this->_getAllParams()))
        {
            echo $form;
            return;
        }

        $values = $form->getValues();

        if(isset($values['image']))
            $image = GD3W_PATH."/img/inf/".$form->image->getFileName(null, false);
        else
            $image = '';

        $this->sql->updateInfo($iIdInfo,$values['title'],$values['description'],$image);

        $this->_helper->redirector('index', 'who');
        return;
    }


    /**
     * \brief action para borrar información de la empresa
     *
     */
    public function deleteInfoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'who');
            return;
        }
        if(!$this->_hasParam('info'))
        {
            $this->_helper->redirector('list-info', 'who');
            return;
        }

        $iIdInfo = $this->getRequest()->getParam('info');

        $this->sql->deleteInfo($iIdInfo);

        $this->_helper->redirector('index', 'who');
    }


}
