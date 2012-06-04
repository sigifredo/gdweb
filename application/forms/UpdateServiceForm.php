<?php

class UpdateServiceForm extends CreateServiceForm
{
    public function init()
    {
        parent::init();
        $this->_acceptButton->setLabel('Actualizar');
    }
}
