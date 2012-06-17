<?php

class TbUser extends GDWebDbTableAbstract
{
    protected $_name = "tb_user";
    protected $_primary = "cc";

    public function insert($aData)
    {
        if(isset($aData['lastnames']) && $aData['lastnames'] == '')
            unset($aData['lastnames']);

        if(isset($aData['telephone']) && $aData['telephone'] == '')
            unset($aData['telephone']);

        if(isset($aData['movil']) && $aData['movil'] == '')
            unset($aData['movil']);

        parent::insert($aData);
    }

    public function update($aData, $where)
    {
        if(isset($aData['lastnames']) && $aData['lastnames'] == '')
            unset($aData['lastnames']);

        if(isset($aData['telephone']) && $aData['telephone'] == '')
            unset($aData['telephone']);

        if(isset($aData['movil']) && $aData['movil'] == '')
            unset($aData['movil']);

        parent::update($aData, $where);
    }

    public function delete($where, $virtual = true)
    {
        if($virtual)
            parent::update(array('activated'=>'false'), $where);
        else
            parent::delete($where);
    }

}
