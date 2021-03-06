<?php

class CreateInfoForm extends Zend_Form
{
    protected $_acceptButton = null;

    public function init()
    {
        $this->setAttrib('class','createinfo');

        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Leer imagen...')
              ->setDestination(GD3W_PATH."/img/inf/")
              ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $this->addElement($image);
        $this->addElement('textarea', 'title', array('label'=>'Título (*)', 'required'=>true, 'rows'=>1));
        $this->addElement('textarea', 'description', array('label'=>'Descripción (*)', 'required'=>true));

        $this->_acceptButton = new Zend_Form_Element_Submit('create', array('label'=>'Crear'));
        $this->addElement($this->_acceptButton);
    }
}
