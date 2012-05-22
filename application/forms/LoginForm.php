<?php

class LoginForm extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('class','login');

        $this->addElement('text','user',array('label'=>'User','required'=>true,'filter'=>'StringToLower','validator'=>'StringLength',false,array(6,10),'validator'=>'alnum','validator'=>'regex', false, array('/^[a-z]+/')));
        $this->addElement('password','password',array('label'=>'Password','required'=>true,'validator'=>'StringLength',false,array(6,40)));
        $this->addElement('submit','login',array('label'=>'Login'));
    }

}
