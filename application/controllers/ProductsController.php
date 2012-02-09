<?php

class ProductsController extends Zend_Controller_Action
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

    /**
     * \brief Formulario para crear proyecto
     *
     * @return Formulario a action createProyect
     *
     */

    public function createProyectForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','createproyect');
        $form->setAction($this->view->url(array("controller" => "products", "action" => "create-proyect")))
        ->setMethod('post');

        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Load the image')
        ->setDestination(APPLICATION_PATH."/../public/img/proy/")
        ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $form->addElement($image);

        $form->addElement('select','type',array('label' => 'Type', 'multiOptions' => array('1' => 'Open Source','2' => 'Non-Free'),'required' => true));

        $form->addElement('text','name',array('label'=>'Name','required'=>true,'validator'=>'StringLength',false,array(1,20),'validator'=>'alnum'));

        $form->addElement('textarea','description',array('label'=>'Description','required'=>true));

        $form->addElement('text','client',array('label'=>'Client','required'=>true,'filter'=>'StringToLower','validator'=>'StringLength',false,array(6,10),'validator'=>'alnum','validator'=>'regex', false, array('/^[a-z]+/')));

        $form->addElement('submit','create',array('label'=>'Create'));

        return $form;
    }


    public function indexAction()
    {
        $this->view->proyect = $this->sql->listProyects();
        return;
    }

    /**
     * \brief action para crear proyecto
     *
     * @return N/A
     *
     */

    public function createProyectAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        $form = $this->createProyectForm();

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

