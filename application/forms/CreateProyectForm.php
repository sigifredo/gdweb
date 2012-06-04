<?php

class CreateProyectForm extends Zend_Form
{
    protected $_acceptButton = null;

    public function init()
    {
        $this->setAttrib('class','createproyect');
    
        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Leer imagen...')
              ->setDestination(GD3W_PATH."/img/proy/")
              ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs
    
        $this->addElement($image);
        $this->addElement('select','type',array('label' => 'Tipo (*)', 'multiOptions' => array('1' => 'Open Source','2' => 'Non-Free'),'required' => true));
        $this->addElement('text','name',array('label'=>'Nombre (*)','required'=>true, 'validators' => array(array('validator' => 'StringLength', 'options' => array(0, 40)))));
        $this->addElement('textarea','description',array('label'=>'Descripción (*)', 'required'=>true));
        $this->addElement('text','cc_client',array('label'=>'Cliente (*)','required'=>true, 'validators' => array(array('validator' => 'StringLength', 'options' => array(0, 10)))));

        $this->_acceptButton = new Zend_Form_Element_Submit('create', array('label'=>'Crear'));
        $this->addElement($this->_acceptButton);
    }
}
