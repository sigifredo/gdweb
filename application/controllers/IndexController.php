<?php

class IndexController extends Zend_Controller_Action
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

    /**
     * \brief Formulario para modificar memos
     *
     * @return Formulario a action updateMemo
     *
     */
    public function updateMemoForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','updatememo');
        $form->setAction($this->view->url(array("controller" => "index", "action" => "update-memo")))
        ->setMethod('post');

        $form->addElement('text','title',array('label'=>'Title','validator'=>'StringLength',false,array(1,20),'validator'=>'alnum'));

        $form->addElement('textarea','description',array('label'=>'Description'));

        $form->addElement('submit','update',array('label'=>'Update'));

        return $form;
    }

    /**
     * \brief action para noticias e ingreso de usuario
     *
     */
    public function indexAction()
    {
        $this->view->headTitle("Inicio");

        $tbNews = new TbNews();
        $this->view->news = $tbNews->select()->query()->fetchAll();
    }

    /**
     * \brief action para listar memos
     *
     */
    public function listMemoAction()
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

    /**
     * \brief action para modificar memos
     *
     */
    public function updateMemoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        if(!$this->_hasParam('memo') && !$this->_hasParam('cc'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }

        $form = $this->updateMemoForm();
        $iIdMemo = $this->getRequest()->getParam('memo');
        $datos = $this->sql->listMemos($this->getRequest()->getParam('cc'));

        if(!$this->getRequest()->isPost())
        {
            echo "<h4 id='infmemo'>Nuevos Datos De Memorando</h4>";

            foreach($datos as $line)
            {
                if($line['id'] == $iIdMemo)
                {
                    echo $form->populate($line);
                }
            }
            return;
        }

        if(!$form->isValid($this->_getAllParams()))
        {
            echo $form;
            return;
        }

        $values = $form->getValues();

        $this->sql->updateMemo($iIdMemo,$values['title'],$values['description']);

        $this->_helper->redirector('index', 'index');
        return;
    }

    /**
     * \brief action para borrar memo
     *
     * @return N/A
     *
     */

    public function deleteMemoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }

        if(!$this->_hasParam('memo'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }

        $iIdMemo = $this->getRequest()->getParam('memo');

        $this->sql->deleteMemo($iIdMemo);

        $this->_helper->redirector('index', 'index');
        return;
    }

    public function viewAction()
    {
        if(!$this->_hasParam('i'))
            $this->_helper->redirector('index', 'index');
        else
        {
            $this->view->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);

            $tbImage = new TbImage();

            $image = $tbImage->getImage($this->getRequest()->getParam('i'));

            header("Content-Type: image/".$image->type());
            header("Content-Disposition: inline; filename=".$image->name());
            fpassthru($image->content());
        }
    }

}

