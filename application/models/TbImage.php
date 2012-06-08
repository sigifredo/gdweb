<?php

class TbImage extends Zend_Db_Table_Abstract
{
    protected $_name = "tb_image";
    protected $_primary = "id";

    public function insert($rImage)
    {
        $dbAdapter = parent::getDefaultAdapter();
        $query = $dbAdapter->prepare("INSERT INTO tb_image (name, content, type) VALUES (?, ?, ?)");

        $query->bindParam(1, $rImage->name());
        $query->bindParam(2, $rImage->content(), PDO::PARAM_LOB);
        $query->bindParam(3, $rImage->type());

        $query->execute();

        return $dbAdapter->lastSequenceId("tb_image_id_seq");;
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
