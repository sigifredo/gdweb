<?php

class UpdateInfoForm extends CreateInfoForm
{
    public function init()
    {
        parent::init();
        $this->_acceptButton->setLabel('Actualizar');
    }
}
