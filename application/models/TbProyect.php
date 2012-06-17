<?php

class TbProyect extends GDWebDbTableAbstract
{
    protected $_name = "tb_proyect";
    protected $_primary = "id";

    public function insert($aData)
    {
        if(isset($aData['cc_client']) && $aData['cc_client'] == '')
            unset($aData['cc_client']);

        parent::insert($aData);
    }

    public function update($aData, $where)
    {
        if(isset($aData['cc_client']) && $aData['cc_client'] == '')
            unset($aData['cc_client']);

        parent::update($aData, $where);
    }
}
