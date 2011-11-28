<?php

class WhoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->headTitle("¿Quiénes somos?");
    }

}
