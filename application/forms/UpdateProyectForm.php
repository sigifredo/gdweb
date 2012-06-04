<?php

class UpdateProyectForm extends CreateProyectForm
{
    public function init()
    {
        parent::init();
        $this->_acceptButton->setLabel('Actualizar');
    }
}
