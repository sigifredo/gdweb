<?php

class CreateServiceForm extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('class', 'createservice');

        $this->addElement('textarea', 'name', array('label'=>'Name (*)', 'required'=>true, 'rows'=>1, 'validators' => array(array('validator' => 'StringLength', 'options' => array(0, 20)))));
        $this->addElement('textarea', 'description', array('label'=>'Descripción (*)', 'required'=>true));
        $this->addElement('submit', 'create', array('label'=>'Crear'));
    }
}
