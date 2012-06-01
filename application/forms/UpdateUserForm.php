<?php

class UpdateUserForm extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('class','updateusr');

        $image = new Zend_Form_Element_File('updateuser');
        $image->setLabel('Leer imagen...')
              ->setDestination(GD3W_PATH."/img/usr/")
              ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $this->addElement($image);
        $this->addElement('text','names',array('label'=>'Nombres','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));
        $this->addElement('text','lastnames',array('label'=>'Apellidos','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));
        $this->addElement('password','newpassword',array('label'=>'Nueva contraseña','validator'=>'StringLength',false,array(6,40)));
        $this->addElement('password','verifypassword',array('label'=>'Verificar contraseña','validator'=>'StringLength',false,array(6,40)));
        $this->addElement('text','telephone',array('label'=>'Teléfono','validator'=>'digits','validator'=>'StringLength',false,array(0,7)));
        $this->addElement('text','movil',array('label'=>'Celular','validator'=>'digits','validator'=>'StringLength',false,array(0,10)));
        $this->addElement('submit','update',array('label'=>'Actualizar'));
    }

}
