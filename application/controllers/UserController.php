<?php

class UserController extends Zend_Controller_Action
{

    private $session = null;

    private $auth = null;

    private $sql = null;

    /**
     * \brief Contruye las variables de la clase
     *
     * @return N/A
     *
     *
     *
     */
    public function init()
    {
        $this->sql = new Application_Model_SQL();
        $this->session=new Zend_Session_Namespace('Users');
        $this->auth = Zend_Auth::getInstance();
    }

    /**
     * \brief Formulario para crear usuario
     *
     * @return Formulario a action createUser
     *
     *
     *
     */
    public function createUserForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','createuser');
        $form->setAction($this->view->url(array("controller" => "user", "action" => "create-user")))
        ->setMethod('post');

        $image = new Zend_Form_Element_File('user');
        $image->setLabel('Load the image')
        ->setDestination(APPLICATION_PATH."/../public/img/usr/")
        ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $form->addElement($image);

        $form->addElement('text','cc',array('label'=>'CC','required'=>true,'validator'=>'StringLength',false,array(6,10),'validator'=>'alnum'));

        $form->addElement('password','password',array('label'=>'Password','validator'=>'StringLength',false,array(6,40)));

        $form->addElement('password','verifypassword',array('label'=>'Verify Password','validator'=>'StringLength',false,array(6,40)));

        $form->addElement('text','names',array('label'=>'Names','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));

        $form->addElement('text','lastnames',array('label'=>'Last Names','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));

        $form->addElement('text','telephone',array('label'=>'Telephone','validator'=>'digits','validator'=>'StringLength',false,array(0,7)));

        $form->addElement('text','movil',array('label'=>'Movil','validator'=>'digits','validator'=>'StringLength',false,array(0,10)));

        $form->addElement('submit','create',array('label'=>'Create'));

        return $form;
    }

    /**
     * \brief Formulario para modificar usuario
     *
     * @return Formulario a action updateUser
     *
     *
     *
     */
    public function updateUserForm()
    {
        $form=new Zend_Form;
        $form->setAttrib('class','updateusr');
        $form->setAction($this->view->url(array("controller" => "user", "action" => "update-user")))->setMethod('post');

        $image = new Zend_Form_Element_File('updateuser');
        $image->setLabel('Load the image')
        ->setDestination(APPLICATION_PATH."/../public/img/usr/")
        ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $form->addElement($image);

        $form->addElement('text','names',array('label'=>'Names','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));

        $form->addElement('text','lastnames',array('label'=>'Last Names','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));

        $form->addElement('password','newpassword',array('label'=>'New Password','validator'=>'StringLength',false,array(6,40)));

        $form->addElement('password','verifypassword',array('label'=>'Verify Password','validator'=>'StringLength',false,array(6,40)));

        $form->addElement('text','telephone',array('label'=>'Telephone','validator'=>'digits','validator'=>'StringLength',false,array(0,7)));

        $form->addElement('text','movil',array('label'=>'Movil','validator'=>'digits','validator'=>'StringLength',false,array(0,10)));

        $form->addElement('submit','update',array('label'=>'Update'));

        return $form;

    }

    /**
     * \brief action para crear usuario
     *
     * @return N/A
     *
     *
     *
     */
    public function createUserAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        if(!$this->_hasParam('usr'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }

        $iUserType = $this->getRequest()->getParam('usr');

        $form = $this->createUserForm();

        if(!$this->getRequest()->isPost())
        {
            echo "<h4 id='infuser'>Datos del Usuario</h4>";
            echo $form;
            return;
        }
        if(!$form->isValid($this->_getAllParams()))
        {
            echo $form;
            return;
        }

        $values = $form->getValues();

        if($values['password'] != $values['verifypassword'])
        {
            echo "La contraseña no coincide";
            echo $form;
            return;
        }

        if(isset($values['user']))
        {
            $image = APPLICATION_PATH."/../public/img/usr/".$form->user->getFileName(null,false);
        }
        else
        {
            $image = '';
        }

        switch ($iUserType)
        {
        case 1:

            $this->sql->insertAdmin($values['cc'],sha1($values['password']),$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
            break;

        case 2:

            $this->sql->insertClient($values['cc'],sha1($values['password']),$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
            break;

        case 3:

            $this->sql->insertDeveloper($values['cc'],sha1($values['password']),$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
            break;
        }

        $this->_helper->redirector('index', 'index');
        return;
    }

    /**
     * \brief action para modificar usuario
     *
     * @return N/A
     *
     *
     *
     */
    public function updateUserAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        if(!$this->_hasParam('usr'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        if(!$this->_hasParam('cc'))
        {
            $this->_helper->redirector('list-user', 'index');
            return;
        }

        $iUserType = $this->getRequest()->getParam('usr');
        $iCCUser = $this->getRequest()->getParam('cc');

        $form = $this->updateUserForm();

        if(!$this->getRequest()->isPost())
        {
            echo "<h4 id='infusr'>Nuevos Datos De Usuario</h4>";


            switch ($iUserType)
            {
            case 1:

                $datos = $this->sql->listAdmin();

                break;

            case 2:

                $datos = $this->sql->listClient();

                break;

            case 3:

                $datos = $this->sql->listDeveloper();

                break;
            }

            foreach($datos as $line)
            {
                if($line['cc'] == $iCCUser)
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

        if(isset($values['updateusr']))
        {
            $image = APPLICATION_PATH."/../public/img/usr/".$form->user->getFileName(null,false);
        }
        else
        {
            $image = '';
        }

        if(isset($values['newpassword']))
        {

            if($values['newpassword'] != $values['verifypassword'])
            {
                echo "La contraseña no coincide";
                echo $form;
                return;
            }
            $this->sql->updatePassword($iCCUser,sha1($values['newpassword']));
        }

        switch ($iUserType)
        {
        case 1:

            $this->sql->updateAdmin($iCCUser,$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
            break;

        case 2:

            $this->sql->updateClient($iCCUser,$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
            break;

        case 3:

            $this->sql->updateDeveloper($iCCUser,$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
            break;
        }
        $this->_helper->redirector('index', 'index');
        return;
    }

    /**
     * \brief action para borrar usuario
     *
     * @return N/A
     *
     *
     *
     */
    public function deleteUserAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
        {
            $this->_helper->redirector('index', 'index');
            return;
        }

        if(!$this->_hasParam('cc'))
        {
            $this->_helper->redirector('list-user', 'index');
            return;
        }

        $iCCUser = $this->getRequest()->getParam('cc');

        $this->sql->deleteUser($iCCUser);

        $this->_helper->redirector('index', 'index');

        return;
    }

    /**
     * \brief action para mostrar perfiles
     *
     * @return N/A
     *
     *
     */
    public function profileAction()
    {
        if(!$this->auth->hasIdentity())
        {
            $this->_helper->redirector('index', 'index');
            return;
        }
        elseif( $this->session->type == '1')
        {
            echo "<div id='adminMenu' class='menu'>

            <div>
            <span>Cuentas</span>
            <ul>
            <a href=".$this->view->url(array('controller'=>'user', 'action'=>'create-user','usr'=>'1')).">Crear Cuenta Administrador</a><br>
            <a href=".$this->view->url(array('controller'=>'user', 'action'=>'create-user','usr'=>'2')).">Crear Cuenta Cliente</a><br>
            <a href=".$this->view->url(array('controller'=>'user', 'action'=>'create-user','usr'=>'3')).">Crear Cuenta Desarrollador</a><br>
            <a href=".$this->view->url(array('controller'=>'index', 'action'=>'list-user','usr'=>'1')).">Editar Cuenta Administrador</a><br>
            <a href=".$this->view->url(array('controller'=>'index', 'action'=>'list-user','usr'=>'2')).">Editar Cuenta Cliente</a><br>
            <a href=".$this->view->url(array('controller'=>'index', 'action'=>'list-user','usr'=>'3')).">Editar Cuenta Desarrollador</a><br>
            </ul>
            </div>

            <div>
            <span>Noticias</span>
            <ul>
            <a href=".$this->view->url(array('controller'=>'index', 'action'=>'create-news')).">Crear Noticia</a><br>
            <a href=".$this->view->url(array('controller'=>'index', 'action'=>'list-news')).">Editar Noticia</a><br>
            </ul>
            </div>

            <div>
            <span>Memorandos</span>
            <ul>
            <a href=".$this->view->url(array('controller'=>'index', 'action'=>'create-memo')).">Crear Memorando</a><br>
            <a href=".$this->view->url(array('controller'=>'index', 'action'=>'list-memo', 'typememo'=>'edit')).">Editar Memorando</a><br>
            </ul>
            </div>

            <div>
            <span>Proyectos</span>
            <ul>
            <a href=".$this->view->url(array('controller'=>'products', 'action'=>'create-proyect')).">Crear Proyecto</a><br>
            <a href=".$this->view->url(array('controller'=>'products', 'action'=>'list-proyect')).">Editar proyecto</a><br>
            </ul>
            </div>
            </div>";
            $this->view->datos = $this->sql->listAdmin();
        }

        switch ($this->session->type)
        {
        case 2:

            $this->view->datos = $this->sql->listClient();
            break;

        case 3:

            $this->view->datos = $this->sql->listDeveloper();
            echo "<h4>Proyectos:</h4>";
            foreach($this->sql->listProyects($this->session->user) as $line)
            {
                echo "
                <table>
                <tr>
                <td>".$line['name']."</td>
                </tr>
                <tr>
                <td>".$line['description']."</td>
                </tr>
                </table>
                </div>";
            };
            break;
        }
        if((!$this->_hasParam('memo')) && ($this->session->type != '2'))
        {
            echo "<a href=".$this->view->url(array('controller'=>'index', 'action'=>'profile', 'memo'=>'list')).">Ver Mis Memorandos</a>";
            return;
        }

        if((!count($this->sql->listMemos($this->session->user))) && ($this->session->type != '2'))
        {
            echo "<h3>El Usuario No Tiene Memorandos</h3>";
            return;
        }
        if(($this->_hasParam('memo')=='list') && ($this->session->type != '2'))
        {
            $this->view->memo = true;
            $this->view->listmemos = $this->sql->listMemos($this->session->user);
            return;
        }

    }

    /**
     * \brief action para borrar session
     *
     * @return N/A
     *
     */
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index', 'index');
    }
}
