<?php

/**
 * version 2
 */

class Application_Model_SQL
{
    protected $dbAdapter = null;
    public function __construct()
    {
        $this->dbAdapter = Zend_Db_Table::getDefaultAdapter();
    }
    /**
     * \brief Obtenemos una instancia de la clase 'Zend_Auth_Adapter_DbTable'.
     *
     * @param $table Tabla en la que se verificará que los datos de usuario son correctos.
     * @param $cc_user Número de indentificación del usuario que queremos autenticar.
     * @param $password Contraseña del usuario a autenticar.
     *
     * @return Adaptador a la base de datos de la clase Zend_Auth_Adapter_DbTable.
     *
     */
    public function getAuthDbTable($table, $cc_user, $password)
    {
        return new Zend_Auth_Adapter_DbTable($this->dbAdapter, $table, $cc_user, $password);
    }

    /**
     * \brief Inserta un administrador en la base de datos.
     *
     * @param $sCC Cédula del administrador.
     * @param $sPassword Contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     * @param $sNames Nombres del administrador.
     * @param $sLastNames Apellidos del administrador.
     * @param $sTelephone Teléfono del administrador.
     * @param $sMovil Celular del administrador.
     * @param $sImage Ruta a la imagen donde está el administrador.
     *
     */
    public function insertAdmin($sCC, $sPassword, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage)
    {
        $this->dbAdapter->fetchRow("SELECT * FROM f_insertadmin('$sCC', '$sPassword', '$sLastNames', '$sTelephone', '$sMovil', '$sImage')");
    }

    /**
     * \brief Inserta un cliente en la base de datos.
     *
     * @param $sCC Cédula del cliente.
     * @param $sPassword Contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     * @param $sNames Nombres del cliente.
     * @param $sLastNames Apellidos del cliente.
     * @param $sTelephone Teléfono del cliente.
     * @param $sMovil Celular del cliente.
     * @param $sImage Ruta a la imagen donde está el cliente.
     *
     */
    public function insertClient($sCC, $sPassword, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage)
    {
        $this->dbAdapter->fetchRow("SELECT * FROM f_insertclient('$sCC', '$sPassword', '$sLastNames', '$sTelephone', '$sMovil', '$sImage')");
    }

    /**
     * \brief Inserta un programador en la base de datos.
     *
     * @param $sCC Cédula del programador.
     * @param $sPassword Contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     * @param $sNames Nombres del programador.
     * @param $sLastNames Apellidos del programador.
     * @param $sTelephone Teléfono del programador.
     * @param $sMovil Celular del programador.
     * @param $sImage Ruta a la imagen donde está el programador.
     *
     */
    public function insertDeveloper($sCC, $sPassword, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage)
    {
        $this->dbAdapter->fetchRow("SELECT * FROM f_insertdeveloper('$sCC', '$sPassword', '$sLastNames', '$sTelephone', '$sMovil', '$sImage')");
    }

    /**
     * \brief Obtenemos la información de la empreza.
     * Esta información puede ser, ¿Quiénes somos?, misión, visión, etc.
     *
     * @return Lista con la información. Cada registro está ordenado así: [id, title, description, image]
     *
     */
    public function listInformation()
    {
        $r = $this->dbAdapter->fetchAll("SELECT id, title, description, image FROM tb_information");
        foreach($r as $row)
        {
            $this->dbAdapter->fetchRow("SELECT lo_export(".$row['image'].", '".getcwd()."/img/inf/".$row['image']."')");
            $row['image'] = getcwd()."/img/inf/".$row['id'];
        }
        return $r;
    }

    /**
     * \brief Obtenemos una lista de noticias.
     *
     * @return Lista de noticias. Cada registro está ordenado así: [id, title, description, image]
     *
     */
    public function listNews()
    {
        $r = $this->dbAdapter->fetchAll("SELECT id, title, description, image FROM tb_news");
        foreach($r as $row)
        {
            $this->dbAdapter->fetchRow("SELECT lo_export(".$row['image'].", '".getcwd()."/img/news/".$row['image']."')");
            $row['image'] = getcwd()."/img/news/".$row['id'];
        }
        return $r;
    }

    /**
     * \brief Obtenemos el tipo del usuario en el momento de autenticarse.
     *
     * @param $sCCUser Cédula del usuario a validar.
     * @param $sPassword Contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     *
     * @return Si el usuario y la contraseña coinciden, se retornará el tipo de usuario; de lo contrario se retornará null.
     *
     */
    public function userType($sCCUser, $sPassword)
    {
        $r = $this->dbAdapter->fetchRow("SELECT id_usertype AS type FROM tb_user WHERE cc='$sCCUser' AND password='$sPassword'");
        return $r['type'];
    }

}
