<?php

class IndexController extends Zend_Controller_Action
{
    private $session = null;
    private $auth = null;
    private $form = null;
    private $sql = null;


    /**
      * \brief Contruye las variables de la clase
      *
      */
    public function init()
    {
        $this->sql = new Application_Model_SQL();
        $this->session = new Zend_Session_Namespace('Users');
        $this->form = $this->loginForm();
        $this->auth = Zend_Auth::getInstance();
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
     * \brief Formulario para crear memo
     *
     * @return Formulario a action createMemo
     *
     */

    public function createMemoForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','creatememo');
        $form->setAction($this->view->url(array("controller" => "index", "action" => "create-memo")))
        ->setMethod('post');

        $form->addElement('text','user',array('label'=>'Usuario','required'=>true,'filter'=>'StringToLower','validator'=>'StringLength',false,array(6,10),'validator'=>'alnum','validator'=>'regex', false, array('/^[a-z]+/')));

        $form->addElement('text','title',array('label'=>'Title','required'=>true,'validator'=>'StringLength',false,array(1,20),'validator'=>'alnum'));

        $form->addElement('textarea','description',array('label'=>'Description','required'=>true));

        $form->addElement('submit','create',array('label'=>'Create'));

        return $form;
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
     * \brief Formulario para autenticar el usuario.
     *
     * @return Formulario a action login
     *
     */

    public function loginForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','login');
        $form->setAction($this->view->url(array("controller" => "index", "action" => "login")))
        ->setMethod('post');

        $form->addElement('text','user',array('label'=>'User','required'=>true,'filter'=>'StringToLower','validator'=>'StringLength',false,array(6,10),'validator'=>'alnum','validator'=>'regex', false, array('/^[a-z]+/')));

        $form->addElement('password','password',array('label'=>'Password','required'=>true,'validator'=>'StringLength',false,array(6,40)));

        $form->addElement('submit','login',array('label'=>'Login'));

        return $form;
    }


    /**
     * \brief action para noticias e ingreso de usuario
     *
     * @return N/A
     *
     */
    public function indexAction()
    {
        $this->view->headTitle("Inicio");

        if(!$this->auth->hasIdentity())
            echo $this->form;

        $this->view->news = $this->sql->listNews(APPLICATION_PATH."/../public/pg/img/news", $this->view->page = ($this->_hasParam('page')?$this->_getParam('page'):1));
    }

    /**
     * \brief action para auntenticacion del usuario
     *
     * @return N/A
     *
     */
    public function loginAction()
    {
        if(!$this->getRequest()->isPost())
        {
            echo $this->form;
            return;
        }
        if(!$this->form->isValid($this->_getAllParams()))
        {
            echo $this->form;
            return;
        }

        $values = $this->form->getValues();

        $sCCUser=$values['user'];
        $sPassword=sha1($values['password']);

        $authAdapter = $this->sql->getAuthDbTable('tb_user','cc','password');

        $authAdapter->setIdentity($sCCUser);
        $authAdapter->setCredential($sPassword);

        $result = $this->auth->authenticate($authAdapter);

        $this->session->type=$this->sql->userType($sCCUser, $sPassword);
        $this->session->user=$sCCUser;

        if(!$result->isValid())
        {

            if($this->session->type==null)
            {
                echo "<h4 id='error' class='login'>El usuario o la contraseña no coincide</h4>";
                echo $this->form;
            }
        }
        else
            $this->_helper->redirector('index', 'index');
        return;
    }

    /**
     * \brief action para listar memos
     *
     * @return N/A
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
     * \brief action para crear memo
     *
     * @return N/A
     *
     */

    public function createMemoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        $form = $this->createMemoForm();

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
        return;
    }

    /**
     * \brief action para modificar memos
     *
     * @return N/A
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

    /**
     * \brief action para borrar noticia
     *
     */
    public function deleteNewsAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        if(!$this->_hasParam('news'))
        {
            $this->_helper->redirector('list-news', 'index');
            return;
        }

        $iIdNews = $this->getRequest()->getParam('news');

        $this->sql->deleteNews($iIdNews);

        $this->_helper->redirector('index', 'index');
        return;
    }

}

