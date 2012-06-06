<?php

class TbUser extends Zend_Db_Table_Abstract
{
    protected $_name = "tb_user";
    protected $_primary = "cc";

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

// $imagePath = "/var/www/imagenes/1.jpg";
// $db = Zend_Db_Table::getDefaultAdapter();
// 
// $image = file_get_contents($imagePath); 
// 
// $sql = "UPDATE tb_user SET image=?"; 
// 
// $q = $db->prepare($sql); 
// $q->bindParam(1, $image, PDO::PARAM_LOB); 
// $q->execute();
}
