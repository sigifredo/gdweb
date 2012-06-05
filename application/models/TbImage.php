<?php

class TbImage extends Zend_Db_Table_Abstract
{
    protected $_name = "tb_image";
    protected $_primary = "id";

    public function insert($aData)
    {
        $dbAdapter = parent::getDefaultAdapter();
        $query = $dbAdapter->prepare("INSERT INTO tb_user (cc, password, names, lastnames, telephone, movil, id_usertype, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $query->bindParam(1, $aData['cc']);
        $query->bindParam(2, $aData['password']);
        $query->bindParam(3, $aData['names']);

        if(isset($aData['lastnames']) && $aData['lastnames'] != '')
            $query->bindParam(4, $aData['lastnames']);
        else
            $query->bindParam(4, null);

        if(isset($aData['telephone']) && $aData['telephone'] != '')
            $query->bindParam(5, $aData['telephone']);
        else
            $query->bindParam(5, null);

        if(isset($aData['movil']) && $aData['movil'] != '')
            $query->bindParam(6, $aData['movil']);
        else
            $query->bindParam(6, null);

        $query->bindParam(7, $aData['id_usertype']);

        if(isset($aData['image']) && $aData['image'] != '')
        {
            $image = file_get_contents($aData['image']); 
            $query->bindParam(8, $image, PDO::PARAM_LOB);
        }
        else
            $query->bindParam(8, null);

        $query->execute();
    }

    public function getImage($iId)
    {
        $dbAdapter = parent::getDefaultAdapter();
        $q = $dbAdapter->prepare("SELECT name, content, type FROM tb_image WHERE id=?");
        $q->execute(array($iId));
        $q->bindColumn(1, $name);
        $q->bindColumn(1, $content, PDO::PARAM_LOB);
        $q->bindColumn(1, $type);
        $q->fetch(PDO::FETCH_BOUND);

        return new Image($name, $content, $type);;
    }
}
