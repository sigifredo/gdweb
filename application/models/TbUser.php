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
            throw new GDException("No se ha podido insertar la noticia. Por favor verifique que los datos son correctos. $e", 0, $e);
        }
    }

    public function getImage($sCC)
    {
        $dbAdapter = parent::getDefaultAdapter();
        $q = $dbAdapter->prepare("SELECT image FROM tb_user WHERE cc=?");
        $q->execute(array($sCC));
        $q->bindColumn(1, $image, PDO::PARAM_LOB);
        $q->fetch(PDO::FETCH_BOUND);

        return $image;
    }
}
