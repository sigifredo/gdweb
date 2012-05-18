<?php

class NewsController extends Zend_Controller_Action
{
    private $auth = null;

    public function init()
    {
        $this->session=new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    public function indexAction()
    {
        // action body
    }

    /**
     * \brief action para crear noticia
     *
     */
    public function createNewsAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');
        else
        {
            $form = new CreateNewsForm();
            $form->setAction($this->view->url(array("controller" => "index", "action" => "create-news")))
                 ->setMethod('post');

            if(!$this->getRequest()->isPost())
            {
                echo "<h4 id='infnews'>Datos de noticia</h4>";
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
                $image = APPLICATION_PATH."/../public/img/news/".$form->image->getFileName(null, false);
            else
                $image = '';

            $this->sql->insertNews($values['title'],$values['description'],$this->session->user, $image);
            $this->_helper->redirector('index', 'index');
        }
    }
}
