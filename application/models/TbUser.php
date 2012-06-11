<?php

class TbUser extends Zend_Db_Table_Abstract
{
    protected $_name = "tb_user";
    protected $_primary = "cc";

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

            if(isset($aData['lastnames']) && $aData['lastnames'] == '')
                unset($aData['lastnames']);

            if(isset($aData['telephone']) && $aData['telephone'] == '')
                unset($aData['telephone']);

            if(isset($aData['movil']) && $aData['movil'] == '')
                unset($aData['movil']);

            parent::insert($aData);

            $dbAdapter->commit();
        }
        catch(Exception $e)
        {
            $dbAdapter->rollback();
            throw new GDException("No se ha podido insertar el usuario. Por favor verifique que los datos son correctos. $e", 0, $e);
        }
    }

    public function update($aData, $where)
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

                unset($aData['image']);
                $aData['id_image'] = $id_image;
            }

            if(isset($values['lastnames']) && $values['lastnames'] == '')
                unset($values['lastnames']);

            if(isset($values['telephone']) && $values['telephone'] == '')
                unset($values['telephone']);

            if(isset($values['movil']) && $values['movil'] == '')
                unset($values['movil']);

            parent::update($aData, $where);

            $dbAdapter->commit();
        }
        catch(Exception $e)
        {
            $dbAdapter->rollback();
            throw new GDException("No se ha podido actualizar el usuario. Por favor verifique que los datos son correctos.", 0, $e);
        }
    }
}
