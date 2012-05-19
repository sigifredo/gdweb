<?php

class MemoController extends Zend_Controller_Action
{
    private $session = null;
    private $auth = null;
    private $sql = null;

    public function init()
    {
        $this->sql = new Application_Model_SQL();
        $this->session=new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
    }

    /**
     * \brief action para crear memo
     *
     */
    public function createAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        $form = new CreateMemoForm();
        $form->setAction($this->view->url(array("controller" => "index", "action" => "create")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<h4 id='infnews'>Datos De Memorando</h4>";
            echo $form;
            return;
        }
        if(!$form->isValid($this->_getAllParams()))
        {
            echo $form;
            return;
        }
        $values = $form->getValues();

        $this->sql->insertMemo($values['user'],$values['title'],$values['description']);

        $this->_helper->redirector('index', 'index');
    }

    /**
     * \brief Formulario para listar memos
     *
     * @return Formulario a action listMemo
     *
     */
    public function listMemoForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','listmemo');
        $form->setAction($this->view->url(array("controller" => "index", "action" => "list-memo")))
        ->setMethod('post');

        $form->addElement('text','user',array('label'=>'Usuario','required'=>true,'filter'=>'StringToLower','validator'=>'StringLength',false,array(6,10),'validator'=>'alnum','validator'=>'regex', false, array('/^[a-z]+/')));

        $form->addElement('submit','Enviar',array('label'=>'Enviar'));

        return $form;
    }

    /**
     * \brief action para listar memos
     *
     */
    public function listAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type == '2'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }

        $form = $this->ListMemoForm();

        if(!$this->getRequest()->isPost())
        {
            echo "<h4 id='infnews'>Usuario Para Listar Memorandos</h4>";
            echo $form;
            return;
        }
        if(!$form->isValid($this->_getAllParams()))
        {
            echo $form;
            return;
        }

        $values = $form->getValues();

        $this->view->user = $values['user'];


        $iList = $this->getRequest()->getParam('typememo');

        if(!count($this->sql->listMemos($this->view->user)))
        {
            echo "<h3>El Usuario No Tiene Memorandos</h3>";
            return;
        }

        if (($this->session->type == '1') && ($iList == 'edit'))
        {
            $this->view->memos = 1;
            return;
        }
        else
        {
            $this->view->memos = 0;

            return;
        }
    }

}

