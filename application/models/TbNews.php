<?php

class TbNews extends GDWebDbTableAbstract
{
    protected $_name = "tb_news";
    protected $_primary = "id";

    public function insert($aData)
    {
        if(isset($aData['description']) && $aData['description'] == '')
            unset($aData['description']);

        parent::insert($aData);
    }

    public function update($aData, $where)
    {
        if(isset($aData['description']) && $aData['description'] == '')
            unset($aData['description']);

        parent::update($aData, $where);
    }
}
