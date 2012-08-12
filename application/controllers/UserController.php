<?php

class UserController extends Zend_Controller_Action
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

    public function indexAction()
    {
        $this->_helper->redirector('profile', 'user');
    }

    /**
     * \brief action para listar usuarios
     *
     */
    public function listAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');

        if(!$this->_hasParam('type'))
            $this->_helper->redirector('index', 'index');

        $tbUser = new TbUser();
        $this->view->users = $tbUser->select()->where("id_usertype=".$this->getRequest()->getParam('type'))->where("activated=true")->query();
    }

    /**
     * \brief action para crear usuario
     *
     */
    public function createAction()
    {
        if(!$this->auth->hasIdentity() || $this->session->type != '1')
            $this->_helper->redirector('index', 'index');

        if(!$this->_hasParam('type'))
            $this->_helper->redirector('user', 'profile');

        $this->view->headTitle("Crear usuario");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/forms.css');

        $iUserType = $this->getRequest()->getParam('type');

        $form = new CreateUserForm();
        $form->setAction($this->view->url(array("controller" => "user", "action" => "create")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Datos del usuario.</span>";
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
        else
        {
            $values['password'] = sha1($values['password']);
            unset($values['verifypassword']);
        }

        if(isset($values['image']))
            $values['image'] = GD3W_PATH."/img/usr/".$form->image->getFileName(null,false);
        else
            $values['image'] = '';

        $tbUser = new TbUser();
        $values['id_usertype'] = $iUserType;
        $tbUser->insert($values);

        $this->_forward('list', 'user');
    }

    /**
     * \brief action para modificar usuario
     *
     */
    public function updateAction()
    {
        if((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');
        if(!$this->_hasParam('cc'))
            $this->_helper->redirector('list', 'index');

        $this->view->headTitle("Actualizar usuario");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/forms.css');

        $form = new UpdateUserForm();
        $form->setAction($this->view->url(array("controller" => "user", "action" => "update")))->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos de usuario.</span>";

            $tbUser = new TbUser();
            $datos = $tbUser->find($this->getRequest()->getParam('cc'));
            $datos = $datos[0]->toArray();
            echo $form->populate($datos);
        }
        else
        {
            if(!$form->isValid($this->_getAllParams()))
                echo $form;
            else
            {
                $values = $form->getValues();

                if($values['image'] == '') // imagen
                    unset($values['image']);
                else
                    $values['image'] = GD3W_PATH."/img/usr/".$form->image->getFileName(null,false);

                if(isset($values['newpassword']) && $values['newpassword'] != '')
                {
                    if($values['newpassword'] != $values['verifypassword'])
                    {
                        echo "La contraseña no coincide.";
                        echo $form;
                        return;
                    }
                    else
                        $values['password'] = sha1($values['newpassword']);
                }

                unset($values['newpassword']);
                unset($values['verifypassword']);

                $tbUser = new TbUser();
                $tbUser->update($values, "cc='".$this->getRequest()->getParam('cc')."'");

                $this->_forward('list', 'user', null, array('type'=>$values['id_usertype']));
            }
        }
    }

    /**
     * \brief action para borrar usuario
     *
     */
    public function deleteAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');
        else
        {
            if(!$this->_hasParam('cc'))
                $this->_helper->redirector('list', 'index');
            else
            {
                $tbUser = new TbUser();
                $tbUser->delete("cc='".$this->getRequest()->getParam('cc')."'");

                $this->_forward('list', 'user', null, array('type'=>$this->getRequest()->getParam('type')));
            }
        }
    }

    /**
     * \brief action para mostrar perfiles
     *
     */
    public function profileAction()
    {
        if(!$this->auth->hasIdentity())
            $this->_helper->redirector('index', 'index');

        $this->view->headTitle("Perfil");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/profile.css');

        $tbUser = new TbUser();
        $this->view->user = $tbUser->find($this->session->user);
        $this->view->user = $this->view->user[0];
        $this->view->session_type = $this->session->type;

        // NPI
        // if((!$this->_hasParam('memo')) && ($this->session->type != '2'))
        // {
        //     echo "<a href=".$this->view->url(array('controller'=>'index', 'action'=>'profile', 'memo'=>'list')).">Ver Mis Memorandos</a>";
        //     return;
        // }

        // if((!count($this->sql->listMemos($this->session->user))) && ($this->session->type != '2'))
        // {
        //     echo "<h3>El Usuario No Tiene Memorandos</h3>";
        //     return;
        // }
        // if(($this->_hasParam('memo')=='list') && ($this->session->type != '2'))
        // {
        //     $this->view->memo = true;
        //     $this->view->listmemos = $this->sql->listMemos($this->session->user);
        //     return;
        // }

    }

    /**
     * \brief acción para cerrar session.
     *
     */
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index', 'index');
    }

    /**
     * \brief acción para auntenticacion del usuario.
     *
     */
    public function loginAction()
    {
        $this->view->headTitle("Iniciar sesión");
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/css/forms.css');

        $form = new LoginForm();
        $form->setAction($this->view->url(array("controller" => "user", "action" => "login")))
             ->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo $form;
            return;
        }
        if(!$form->isValid($this->_getAllParams()))
        {
            echo $form;
            return;
        }

        $values = $form->getValues();

        $sCCUser = $values['user'];
        $sPassword = sha1($values['password']);

        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter, 'tb_user', 'cc', 'password');

        $authAdapter->setIdentity($sCCUser);
        $authAdapter->setCredential($sPassword);

        $result = $this->auth->authenticate($authAdapter);

        $type = $dbAdapter->select()->from("tb_user", "id_usertype")->where("cc='".$sCCUser."'")->where("password='".$sPassword."'")->query()->fetchAll();

        if(!$result->isValid() || $type == null)
        {
            if($type == null)
            {
                echo "<span class='subtitle'>El usuario o la contraseña no coinciden.</span>";
                echo $form;
            }
        }
        else
        {
            $this->session->type = $type[0]['id_usertype'];
            $this->session->user = $sCCUser;

            $this->_helper->redirector('profile', 'user');
        }

    }

}
