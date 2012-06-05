<?php

class UserController extends Zend_Controller_Action
{
    private $session = null;
    private $auth = null;
    private $sql = null;

    /**
     * \brief Contruye las variables de la clase
     *
     */
    public function init()
    {
        $this->sql = new Application_Model_SQL();
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

        $iUserType = $this->getRequest()->getParam('type');
        $this->view->userType = $iUserType;

        switch ($iUserType)
        {
            case 1:
                $this->view->user = $this->sql->listAdmin();
                break;
            case 2:
                $this->view->user = $this->sql->listClient();
                break;
            case 3:
                $this->view->user = $this->sql->listDeveloper();
                break;
        }
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

        if(isset($values['user']))
            $values['image'] = GD3W_PATH."/img/usr/".$form->user->getFileName(null,false);
        else
            $values['image'] = '';

        switch ($iUserType)
        {
            case 1:
$tbUser = new TbUser();
$values['id_usertype'] = $iUserType;
$tbUser->insert($values);
                // $this->sql->insertAdmin($values['cc'],sha1($values['password']),$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
                break;
            case 2:
                $this->sql->insertClient($values['cc'],sha1($values['password']),$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
                break;
            case 3:
                $this->sql->insertDeveloper($values['cc'],sha1($values['password']),$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
                break;
        }

        $this->_forward('list', 'user');
    }

    /**
     * \brief action para modificar usuario
     *
     */
    public function updateAction()
    {
        if ((!$this->auth->hasIdentity()) || ($this->session->type != '1'))
            $this->_helper->redirector('index', 'index');
        if(!$this->_hasParam('cc'))
            $this->_helper->redirector('list', 'index');

        $sCCUser = $this->getRequest()->getParam('cc');

        $form = new UpdateUserForm();
        $form->setAction($this->view->url(array("controller" => "user", "action" => "update")))->setMethod('post');

        if(!$this->getRequest()->isPost())
        {
            echo "<span class='subtitle'>Nuevos datos de usuario.</span>";

            $datos = $this->sql->user($sCCUser);
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
                    $image = '';
                else
                    $image = GD3W_PATH."/img/usr/".$form->image->getFileName(null,false);

                if(isset($values['newpassword']) && $values['newpassword'] != '')
                {
                    if($values['newpassword'] != $values['verifypassword'])
                    {
                        echo "La contraseña no coincide";
                        echo $form;
                        return;
                    }
                    else
                        $this->sql->updatePassword($sCCUser, sha1($values['newpassword']));
                }

                switch ($values['id_usertype'])
                {
                    case 1:
                        $this->sql->updateAdmin($sCCUser,$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
                        break;
                    case 2:
                        $this->sql->updateClient($sCCUser,$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
                        break;
                    case 3:
                        $this->sql->updateDeveloper($sCCUser,$values['names'],$values['lastnames'],$values['telephone'],$values['movil'],$image);
                        break;
                }

                // $this->_forward('list', 'user', null, array('type'=>$values['id_usertype']));
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
                $iCCUser = $this->getRequest()->getParam('cc');
                $this->sql->deleteUser($iCCUser);
                $this->_helper->redirector('index', 'index');
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

        $this->view->user = $this->sql->user($this->session->user);
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

        $authAdapter = $this->sql->getAuthDbTable('tb_user','cc','password');

        $authAdapter->setIdentity($sCCUser);
        $authAdapter->setCredential($sPassword);

        $result = $this->auth->authenticate($authAdapter);

        $this->session->type = $this->sql->userType($sCCUser, $sPassword);
        $this->session->user = $sCCUser;

        if(!$result->isValid())
        {
            if($this->session->type==null)
            {
                echo "<span class='subtitle'>El usuario o la contraseña no coinciden.</span>";
                echo $form;
            }
        }
        else
            $this->_helper->redirector('index', 'index');
    }

}
