<?php

class UpdateInfoForm extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('class','updateinfo');

        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Leer imagen...')
              ->setDestination(GD3W_PATH."/img/inf/")
              ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $this->addElement($image);
        $this->addElement('text','title',array('label'=>'Titulo','validator'=>'StringLength',false,array(1, 20),'validator'=>'alnum'));
        $this->addElement('textarea','description',array('label'=>'Descripción'));
        $this->addElement('submit','update',array('label'=>'Actualizar'));
    }

}
