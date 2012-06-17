<?php

class TbNews extends Zend_Db_Table_Abstract
{
    protected $_name = "tb_news";
    protected $_primary = "id";

    public function insert($aData)
    {
        $dbAdapter = parent::getDefaultAdapter();

        $dbAdapter->beginTransaction();

        try
        {
            $id_image = 1;

            if(isset($aData['image']) && $aData['image'] != '')
            {
                $tbImage = new TbImage();
                $id_image = $tbImage->insert(new Image($aData['image']));
            }

            unset($aData['image']);
            $aData['id_image'] = $id_image;

            if(isset($aData['description']) && $aData['description'] == '')
                unset($aData['description']);

            parent::insert($aData);

            $dbAdapter->commit();
        }
        catch(Exception $e)
        {
            $dbAdapter->rollback();
            throw new GDException("No se ha podido insertar la noticia. Por favor verifique que los datos son correctos.", 0, $e);
        }
    }

    public function update($aData, $where)
    {
        $dbAdapter = parent::getDefaultAdapter();

        $dbAdapter->beginTransaction();

        try
        {
            if(isset($aData['image']) && $aData['image'] != '')
            {
                $tbImage = new TbImage();
                $id_image = $tbImage->insert(new Image($aData['image']));

                unset($aData['image']);
                $aData['id_image'] = $id_image;
            }

            if(isset($values['description']) && $values['description'] == '')
                unset($values['description']);

            parent::update($aData, $where);

            $dbAdapter->commit();
        }
        catch(Exception $e)
        {
            $dbAdapter->rollback();
            throw new GDException("No se ha podido actualizar la noticia. Por favor verifique que los datos son correctos.", 0, $e);
        }
    }
}
