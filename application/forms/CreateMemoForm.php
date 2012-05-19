<?php

class CreateMemoForm extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('class','creatememo');

        $this->addElement('text','user',array('label'=>'Usuario','required'=>true,'filter'=>'StringToLower','validator'=>'StringLength',false,array(6,10),'validator'=>'alnum','validator'=>'regex', false, array('/^[a-z]+/')));
        $this->addElement('text','title',array('label'=>'Title','required'=>true,'validator'=>'StringLength',false,array(1,20),'validator'=>'alnum'));
        $this->addElement('textarea','description',array('label'=>'Description','required'=>true));
        $this->addElement('submit','create',array('label'=>'Create'));
    }

}
