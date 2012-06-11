<?php

class TbInfo extends Zend_Db_Table_Abstract
{
    protected $_name = "tb_info";
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

            parent::insert($aData);

            $dbAdapter->commit();
        }
        catch(Exception $e)
        {
            $dbAdapter->rollback();
            throw new GDException("No se ha podido insertar el proyecto. Por favor verifique que los datos son correctos. $e", 0, $e);
        }
    }

}
