<?php

class CreateProyectForm extends Zend_Form
{
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
        $this->addElement('select','type',array('label' => 'Tipo', 'multiOptions' => array('1' => 'Open Source','2' => 'Non-Free'),'required' => true));
        $this->addElement('text','name',array('label'=>'Nombre','required'=>true,'validator'=>'StringLength',false,array(1,20),'validator'=>'alnum'));
        $this->addElement('textarea','description',array('label'=>'Descripción','required'=>true));
        $this->addElement('text','client',array('label'=>'Cliente','required'=>true,'filter'=>'StringToLower','validator'=>'StringLength',false,array(6,10),'validator'=>'alnum','validator'=>'regex', false, array('/^[a-z]+/')));
        $this->addElement('submit','create',array('label'=>'Create'));
    }
}