<?php

class IndexController extends Zend_Controller_Action
{
    private $session = null;

    private $auth = null;

    private $form = null;
    
    protected $sql=null;

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
      $this->form=$this->loginForm();
      $this->auth = Zend_Auth::getInstance();
        
    }

    /**
     * \brief Formulario para crear usuario
     *
     * @return Formulario a action createUser
     *
     */

    public function createUserForm()
    {
    $form=new Zend_Form;
    $form->setAttrib('class','createuser');
    $form->setAction($this->view->url(array("controller" => "index", "action" => "create-user")))
	 ->setMethod('post');
    
    $image = new Zend_Form_Element_File('user');
    $image->setLabel('Load the image')
	  //->setDestination(APPLICATION_PATH."/../public/img/")
	  ->setMaxFileSize(2097152); // limits the filesize on the client side	  
    $image->addValidator('Count', false, 1);                // ensure only 1 file
    $image->addValidator('Size', false, 2097152);            // limit to 2 meg
    $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

    $form->addElement($image);

    $form->addElement('text','cc',array('label'=>'CC','required'=>true,'validator'=>'StringLength',false,array(6,10),'validator'=>'alnum'));

    $form->addElement('password','password',array('label'=>'Password','required'=>true,'validator'=>'StringLength',false,array(6,40)));

    $form->addElement('text','names',array('label'=>'Names','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));

    $form->addElement('text','lastnames',array('label'=>'Last Names','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));

    $form->addElement('text','telephone',array('label'=>'Telephone','validator'=>'digits','validator'=>'StringLength',false,array(0,7)));

    $form->addElement('text','movil',array('label'=>'Movil','validator'=>'digits','validator'=>'StringLength',false,array(0,10)));

 //   $form->addElement('text','type',array('label'=>'Type User','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,10)));

    $form->addElement('submit','create',array('label'=>'Create'));
    
    return $form;
    }

    /**
     * \brief Formulario para crear noticia
     *
     * @return Formulario a action createNews
     *
     */

    public function createNewsForm()
    {
    $form=new Zend_Form;
    $form->setAttrib('class','createnews');
    $form->setAction($this->view->url(array("controller" => "index", "action" => "create-news")))
	 ->setMethod('post');
    
    $image = new Zend_Form_Element_File('new');
    $image->setLabel('Load the image')
	  //->setDestination(APPLICATION_PATH."/../public/img/")
	  ->setMaxFileSize(2097152); // limits the filesize on the client side	  
    $image->addValidator('Count', false, 1);                // ensure only 1 file
    $image->addValidator('Size', false, 2097152);            // limit to 2 meg
    $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

    $form->addElement($image);

    $form->addElement('text','title',array('label'=>'Title','required'=>true,'validator'=>'StringLength',false,array(1,20),'validator'=>'alnum'));

    $form->addElement('textarea','description',array('label'=>'Description','required'=>true));

 //   $form->addElement('text','cc',array('label'=>'CC','required'=>true,'validator'=>'StringLength',false,array(6,10),'validator'=>'alnum'));

    $form->addElement('submit','create',array('label'=>'Create'));
    
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
        {
	    echo $this->form;
        }
	elseif( $this->session->type == 'admin')
	{
	echo "<div id='adminMenu' class='menu'>

	    <div>
	      <span>Cuentas</span>
	      <ul>
		<a href=".$this->view->url(array('controller'=>'index', 'action'=>'create-user')).">Crear Cuenta</a><br>
		<a href=".$this->view->url(array('controller'=>'index', 'action'=>'update-user')).">Modificar Cuenta</a><br>
		<a href=".$this->view->url(array('controller'=>'index', 'action'=>'delete-user')).">Eliminar Cuenta</a><br>
	      </ul>
	    </div>

	    <div>
	      <span>Noticias</span>	
	      <ul>
		<a href=".$this->view->url(array('controller'=>'admin', 'action'=>'create-news')).">Crear Noticia</a><br>
		<a href=".$this->view->url(array('controller'=>'admin', 'action'=>'update-news')).">Modificar Noticia</a><br>
		<a href=".$this->view->url(array('controller'=>'admin', 'action'=>'delete-news')).">Borrar Noticia</a><br>
	      </ul>
	    </div>
	</div>";
	}

	$this->view->news = $this->sql->listNews();

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
	Zend_Loader::loadClass('Zend_Filter_StripTags');
            $f = new Zend_Filter_StripTags();
            $sCCUser = $f->filter($this->_request->getPost('user'));
            $sPassword = sha1($f->filter($this->_request->getPost('password')));
	    
	    $this->session->type=$this->sql->userType($sCCUser, $sPassword);

	    if($this->session->type==null)
	    {
		echo "<h4 id='error' class='login'>El Usuario y/o la contraseña no coinciden</h4>";
		echo $this->form;
	    }
	    else
	        $this->_helper->redirector('index', 'index');
    }
    
    /**
     * \brief action para crear usuario
     *
     * @return N/A
     *
     */

   public function createUserAction()
   {
    $form = $this->createUserForm();

	if(!$this->getRequest()->isPost())
	{
	    echo $form;
	    return;
	}
	if(!$this->form->isValid($this->_getAllParams()))
	{
	    echo $form;
	    return;
	}
	$values = $form->getValues();
//funcion para ingresar datos (values) a la tabla tb_user
   }

    /**
     * \brief action para crear noticia
     *
     * @return N/A
     *
     */

   public function createNewsAction()
   {
    $form = $this->createNewsForm();

	if(!$this->getRequest()->isPost())
	{
	    echo $form;
	    return;
	}
	if(!$this->form->isValid($this->_getAllParams()))
	{
	    echo $form;
	    return;
	}
	$values = $form->getValues();
//funcion para ingresar datos (values) a la tabla tb_news
   }

}

?>