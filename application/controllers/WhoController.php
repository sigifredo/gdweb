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

    /**
     * \brief Formulario para crear información de la empresa
     *
     * @return Formulario a action createInfo
     *
     */

    public function createInfoForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','createinfo');
        $form->setAction($this->view->url(array("controller" => "who", "action" => "create-info")))
        ->setMethod('post');

        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Load the image')
        ->setDestination(APPLICATION_PATH."/../public/img/inf/")
        ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $form->addElement($image);

        $form->addElement('text','title',array('label'=>'Title','required'=>true,'validator'=>'StringLength',false,array(1,20),'validator'=>'alnum'));

        $form->addElement('textarea','description',array('label'=>'Description','required'=>true));

        $form->addElement('submit','create',array('label'=>'Create'));

        return $form;
    }


    /**
     * \brief Formulario para modificar información de la empresa
     *
     * @return Formulario a action updateInfo
     *
     */

    public function updateInfoForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','updateinfo');
        $form->setAction($this->view->url(array("controller" => "who", "action" => "update-info")))
        ->setMethod('post');

        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Load the image')
        ->setDestination(APPLICATION_PATH."/../public/img/inf/")
        ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $form->addElement($image);

        $form->addElement('text','title',array('label'=>'Title','validator'=>'StringLength',false,array(1,20),'validator'=>'alnum'));

        $form->addElement('textarea','description',array('label'=>'Description'));

        $form->addElement('submit','update',array('label'=>'Update'));

        return $form;
    }

    public function indexAction()
    {
        $this->view->headTitle("¿Quiénes somos?");

        $info = $this->sql->listInformation(APPLICATION_PATH."/../public/pg/img/inf");
        echo "<div id='information'>";

        if(!count($info)) echo "no hay info";
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
       * @return N/A
       *
       */

    public function listInfoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'who');
            return;
        }
        else

            $this->view->info = $this->sql->listInformation(APPLICATION_PATH."/../public/pg/img/inf");

        return;
    }

    /**
     * \brief action para crear información de la empresa
     *
     * @return N/A
     *
     */

    public function createInfoAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'who');
            return;
        }
        $form = $this->createInfoForm();

        if(!$this->getRequest()->isPost())
        {
            echo "<h4 id='infnews'>Datos</h4>";
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
            $image = APPLICATION_PATH."/../public/img/inf/".$form->image->getFileName(null,false);

        }
        else
        {
            $image = '';
        }

        $this->sql->insertInfo($values['title'],$values['description'],$image);

        $this->_helper->redirector('index', 'who');
        return;
    }


    /**
     * \brief action para modificar información de la empresa
     *
     * @return N/A
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
        $form = $this->updateInfoForm();
        $datos = $this->sql->listInformation(APPLICATION_PATH."/../public/pg/img/inf");

        if(!$this->getRequest()->isPost())
        {
            echo "<h4 id='infnews'>Nuevos Datos</h4>";
            foreach($datos as $line)
            {
                if($line['id'] == $iIdInfo)
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

        if(isset($values['image']))
        {
            $image = APPLICATION_PATH."/../public/img/inf/".$form->image->getFileName(null,false);

        }
        else
        {
            $image = '';
        }

        $this->sql->updateInfo($iIdInfo,$values['title'],$values['description'],$image);

        $this->_helper->redirector('index', 'who');
        return;
    }


    /**
     * \brief action para borrar información de la empresa
     *
     * @return N/A
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
        return;
    }


}
