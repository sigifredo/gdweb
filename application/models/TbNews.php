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

    public function getImage($iId)
    {
        $dbAdapter = parent::getDefaultAdapter();
        $q = $dbAdapter->prepare("SELECT name, content, type FROM tb_image WHERE id=?");
        $q->execute(array($iId));
        $q->bindColumn(1, $name);
        $q->bindColumn(2, $content, PDO::PARAM_LOB);
        $q->bindColumn(3, $type);
        $q->fetch(PDO::FETCH_BOUND);

        return new Image($name, $content, $type);;
    }

}
