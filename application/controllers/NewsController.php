<?php

class NewsController extends Zend_Controller_Action
{
    private $auth = null;
    private $session = null;
    private $sql = null;

    public function init()
    {
        $this->sql = new Application_Model_SQL();
        $this->session = new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        $this->_helper->redirector('index', 'index');
    }

    /**
     * \brief action para crear noticia
     *
     */
    public function createAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');
        else
        {
            $form = new CreateNewsForm();
            $form->setAction($this->view->url(array("controller" => "news", "action" => "create")))
                 ->setMethod('post');

            if(!$this->getRequest()->isPost())
            {
                echo "<span class='subtitle'>Datos de noticia.</span>";
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
                $image = GD3W_PATH."/img/news/".$form->image->getFileName(null, false);
            else
                $image = '';

            $this->sql->insertNews($this->session->user, $values['title'], $values['header'], $values['description'], $image);
            $this->_helper->redirector('index', 'index');
        }
    }

    /**
     * \brief action para borrar noticia
     *
     */
    public function deleteAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        if(!$this->_hasParam('news'))
        {
            $this->_helper->redirector('list', 'index');
            return;
        }

        $iIdNews = $this->getRequest()->getParam('news');

        $this->sql->deleteNews($iIdNews);

        $this->_helper->redirector('index', 'index');
    }

    /**
     * \brief action para listar noticias
     *
     */
    public function listAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');
        if($this->_hasParam('page'))
            $this->view->news = $this->sql->listNews($this->_getParam('page'));
        else
            $this->view->news = $this->sql->listNews(1);
    }

    /**
     * \brief action para modificar noticia
     *
     */
    public function updateAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }

        if(!$this->_hasParam('news'))
        {
            $this->_helper->redirector('list', 'index');
            return;
        }

        $iIdNews = $this->getRequest()->getParam('news');
        $form = new UpdateNewsForm();
        $form->setAction($this->view->url(array("controller" => "news", "action" => "update")))
             ->setMethod('post');
        $datos = $this->sql->listNews(1);

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos de noticia.</span>";
            foreach($datos as $line)
                if($line['id'] == $iIdNews)
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
            $image = GD3W_PATH."/img/news/".$form->image->getFileName(null,false);
        else
            $image = '';

        $this->sql->updateNews($iIdNews, $values['title'], $values['header'], $values['description'], $image);

        $this->_helper->redirector('index', 'index');
    }

}
