<?php

class TbNews extends Zend_Db_Table_Abstract
{
    protected $_name = "tb_news";
    protected $_primary = "id";

    public function insert($aData)
    {
        $dbAdapter = parent::getDefaultAdapter();
        $query = $dbAdapter->prepare("INSERT INTO tb_news (title, header, description, cc_owner, id_image) VALUES (?, ?, ?, ?, ?)");

        $query->bindParam(1, $aData['title']);
        $query->bindParam(2, $aData['header']);
        if(isset($aData['description']) && $aData['description']!='')
            $query->bindParam(3, $aData['description']);
        else
            $query->bindParam(3, null);
        $query->bindParam(4, $aData['cc_owner']);

        $tbImage = new TbImage();
        $tbImage->insert(new Image($aData['image']));

        $query->bindParam(5, $dbAdapter->lastInsertId());

        $query->execute();
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
