<?php

class NewsController extends Zend_Controller_Action
{

    private $auth = null;
    private $session = null;

    public function init()
    {
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
            $this->view->headTitle("Crear noticia");
            $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/forms.css');

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
            $values['cc_owner'] = $this->session->user;

            if(isset($values['image']))
                $values['image'] = GD3W_PATH."/img/news/".$form->image->getFileName(null, false);
            else
                $values['image'] = '';

            $tbNews = new TbNews();
            $tbNews->insert($values);

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

        $tbNews = new TbNews();
        $tbNews->delete("id=".$this->getRequest()->getParam('news'));

        $this->_forward('list', 'news');
    }

    /**
     * \brief action para listar noticias
     *
     */
    public function listAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        $tbNews = new TbNews();
        $this->view->news = $tbNews->select()->query()->fetchAll();
    }

    /**
     * \brief action para modificar noticia
     *
     */
    public function updateAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        if(!$this->_hasParam('news'))
            $this->_helper->redirector('list', 'index');

        $form = new UpdateNewsForm();
        $form->setAction($this->view->url(array("controller" => "news", "action" => "update")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos de noticia.</span>";

            $tbNews = new TbNews();
            $datos = $tbNews->find($this->getRequest()->getParam('news'));
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
                $values['image'] = GD3W_PATH."/img/news/".$form->image->getFileName(null,false);

            $tbNews = new TbNews();
            $tbNews->update($values, "id=".$this->getRequest()->getParam('news'));

            $this->_helper->redirector('index', 'index');
        }
    }

    public function viewAction()
    {
        if(!$this->_hasParam('n'))
            $this->_helper->redirector('index', 'index');
        else
        {
            $tbNews = new TbNews();
            $this->view->news = $tbNews->find($this->getRequest()->getParam('n'));
            $this->view->news = $this->view->news[0];

            if(count($this->view->news))
                $this->view->headTitle($this->view->news->title);
        }
    }

}

