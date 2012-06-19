<?php

class UpdateNewsForm extends CreateNewsForm
{
    public function init()
    {
        parent::init();
        $this->_acceptButton->setLabel('Actualizar');
    }
}
