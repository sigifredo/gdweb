<?php

class UpdateUserForm extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('class','updateusr');

        $image = new Zend_Form_Element_File('image');
        $image->setLabel('Leer imagen...')
              ->setDestination(GD3W_PATH."/img/usr/")
              ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $this->addElement($image);
        $this->addElement('text','names',array('label'=>'Nombres (*)','required'=>true, 'validators' => array(array('validator' => 'StringLength', 'options' => array(0, 25)))));
        $this->addElement('text','lastnames',array('label'=>'Apellidos', 'validators' => array(array('validator' => 'StringLength', 'options' => array(0, 25)))));
        $this->addElement('hidden','id_usertype');
        $this->addElement('password','newpassword',array('label'=>'Nueva contraseña'));
        $this->addElement('password','verifypassword',array('label'=>'Verificar contraseña'));
        $this->addElement('text','telephone',array('label'=>'Teléfono', 'validators' => array(array('validator' => 'StringLength', 'options' => array(0, 7)))));
        $this->addElement('text','movil',array('label'=>'Celular', 'validators' => array(array('validator' => 'StringLength', 'options' => array(0, 10)))));
        $this->addElement('submit','update',array('label'=>'Actualizar'));
    }

}
