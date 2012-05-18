<?php

class UpdateUserForm extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('class','updateusr');

        $image = new Zend_Form_Element_File('updateuser');
        $image->setLabel('Load the image')
              ->setDestination(APPLICATION_PATH."/../public/img/usr/")
              ->setMaxFileSize(2097152); // limits the filesize on the client side
        $image->addValidator('Count', false, 1);                // ensure only 1 file
        $image->addValidator('Size', false, 2097152);            // limit to 2 meg
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif');// only JPEG, PNG, and GIFs

        $this->addElement($image);
        $this->addElement('text','names',array('label'=>'Names','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));
        $this->addElement('text','lastnames',array('label'=>'Last Names','required'=>true,'filter'=>'StringToLower','validator'=>'alfa','validator'=>'StringLength',false,array(4,25)));
        $this->addElement('password','newpassword',array('label'=>'New Password','validator'=>'StringLength',false,array(6,40)));
        $this->addElement('password','verifypassword',array('label'=>'Verify Password','validator'=>'StringLength',false,array(6,40)));
        $this->addElement('text','telephone',array('label'=>'Telephone','validator'=>'digits','validator'=>'StringLength',false,array(0,7)));
        $this->addElement('text','movil',array('label'=>'Movil','validator'=>'digits','validator'=>'StringLength',false,array(0,10)));
        $this->addElement('submit','update',array('label'=>'Update'));
    }

}
