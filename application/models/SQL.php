<?php

/**
 * version 14
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
     * \brief Elimina una publicación de información del sistema.
     *
     * @param $iId Número de identificación de la información.
     *
     */
    public function deleteInfo($iId)
    {
        $this->dbAdapter->fetchRow("DELETE FROM tb_info WHERE id=$iId");
    }

    /**
     * \brief Eliminar memo. Este método elimina el memo de forma virtual.
     *
     * @param $iId Número de identificación del memo.
     *
     */
    public function deleteMemo($iId)
    {
        $this->dbAdapter->fetchRow("UPDATE tb_memo SET activated=FALSE WHERE id=$iId");
    }

    /**
     * \brief Elimina un proyecto de la base de datos.
     *
     * @param $iIdProycto Número de indentificación del proyecto que vamos a eliminar.
     *
     */
    public function deleteProyect($iIdProyect)
    {
        $this->dbAdapter->fetchRow("DELETE FROM tb_proyect WHERE id=$iIdProyect");
    }

    /**
     * \brief Elimina una noticia de la base de datos.
     *
     * @param $iId Número de identificación de la noticia.
     *
     */
    public function deleteNews($iId)
    {
        $this->dbAdapter->fetchRow("DELETE FROM tb_news WHERE id=$iId");
    }

    /**
     * \brief Elimina virtualmente un usuario de la base de datos.
     *
     * @param $sCC Cédula del usuario que vamos a eliminar.
     *
     */
    public function deleteUser($sCC)
    {
        $this->dbAdapter->fetchRow("UPDATE tb_user SET activated=FALSE WHERE cc='$sCC'");
    }

    /**
     * \brief Elimina un servicio de la base de datos.
     *
     * @param $iIdService Número de indentificación del servicio que vamos a eliminar.
     *
     */
    public function deleteService($iIdService)
    {
        $this->dbAdapter->fetchRow("DELETE FROM tb_service WHERE id=$iIdService");
    }

    /**
     * \brief Inserta un administrador en la base de datos.
     *
     * @param $sCC Cédula del administrador.
     * @param $sPassword Contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     * @param $sNames Nombres del administrador.
     * @param $sLastNames Apellidos del administrador. Este parámetro es opcional.
     * @param $sTelephone Teléfono del administrador. Este parámetro es opcional.
     * @param $sMovil Celular del administrador. Este parámetro es opcional.
     * @param $sImage Ruta a la imagen donde está el administrador. Este parámetro es opcional.
     *
     */
    public function insertAdmin($sCC, $sPassword, $sNames, $sLastNames = '', $sTelephone = '', $sMovil = '', $sImage = '')
    {
        $this->insertUser($sCC, $sPassword, 1, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage);
    }

    /**
     * \brief Inserta un cliente en la base de datos.
     *
     * @param $sCC Cédula del cliente.
     * @param $sPassword Contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     * @param $sNames Nombres del cliente.
     * @param $sLastNames Apellidos del cliente. Este parámetro es opcional.
     * @param $sTelephone Teléfono del cliente. Este parámetro es opcional.
     * @param $sMovil Celular del cliente. Este parámetro es opcional.
     * @param $sImage Ruta a la imagen donde está el cliente. Este parámetro es opcional.
     *
     */
    public function insertClient($sCC, $sPassword, $sNames, $sLastNames = '', $sTelephone = '', $sMovil = '', $sImage = '')
    {
        $this->insertUser($sCC, $sPassword, 2, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage);
    }

    /**
     * \brief Inserta un programador en la base de datos.
     *
     * @param $sCC Cédula del programador.
     * @param $sPassword Contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     * @param $sNames Nombres del programador.
     * @param $sLastNames Apellidos del programador. Este parámetro es opcional.
     * @param $sTelephone Teléfono del programador. Este parámetro es opcional.
     * @param $sMovil Celular del programador. Este parámetro es opcional.
     * @param $sImage Ruta a la imagen donde está el programador. Este parámetro es opcional.
     *
     */
    public function insertDeveloper($sCC, $sPassword, $sNames, $sLastNames = '', $sTelephone = '', $sMovil = '', $sImage = '')
    {
        $this->insertUser($sCC, $sPassword, 3, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage);
    }

    /**
     * \brief Insertamos un servicio en la base de datos.
     *
     * @param $sName Nombre del servicio.
     * @param $sDescription Descripción del servicio.
     * @param $sCCOwner Cédula del usuario que publica el servicio.
     *
     *
     */
    public function insertService($sName, $sDescription, $sCCOwner)
    {
        $this->dbAdapter->fetchRow("INSERT INTO tb_service (name, description, cc_owner) VALUES ('$sName', '$sDescription', '$sCCOwner')");
    }

    /**
     * \brief Inserta un usuario en la base de datos.
     *
     * @param $sCC Cédula del usuario.
     * @param $sPassword Contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     * @param $iUserType Tipo de usuario a insertar [1 => Administrador, 2 => Cliente, 3 => Desarrollador].
     * @param $sNames Nombres del usuario.
     * @param $sLastNames Apellidos del usuario.
     * @param $sTelephone Teléfono del usuario.
     * @param $sMovil Celular del usuario.
     * @param $sImage Ruta a la imagen donde está el usuario. Este parámetro es opcional.
     *
     */
    protected function insertUser($sCC, $sPassword, $iUserType, $sNames, $sLastNames = '', $sTelephone = '', $sMovil = '', $sImage = '')
    {
        try
        {
            $cols = "cc, password, names, id_usertype";
            $vals = "'$sCC', '$sPassword', '$sNames', $iUserType";

            if($sLastNames != '')
            {
                $cols .= ", lastnames";
                $vals .= ", '$sLastNames'";
            }
            if($sTelephone != '')
            {
                $cols .= ", telephone";
                $vals .= ", '$sTelephone'";
            }
            if($sMovil != '')
            {
                $cols .= ", movil";
                $vals .= ", '$sMovil'";
            }
            if($sImage != '')
            {
                $cols .= ", image";
                $vals .= ", lo_import('$sImage')";
            }

            $this->dbAdapter->fetchRow("INSERT INTO tb_user ($cols) VALUES ($vals)");
        }
        catch(Exception $e)
        {
            throw new GDException("No se ha podido crear el usuario. Por favor verifique que los datos son correctos.", 0, $e);
        }
    }

    /**
     * \brief Inserta información empresarial.
     *
     * @param $sTitle Titulo de la información, ejemplo: Misión.
     * @param $sDescription Descripción o cuerpo de la información.
     * @param $sImage Ruta una imágen representativa de la información. Este campo es opcional.
     *
     */
    public function insertInfo($sTitle, $sDescription, $sImage = '')
    {
        try
        {
            $this->dbAdapter->fetchRow("SELECT * FROM f_insertinfo('$sTitle', '$sDescription', '$sImage')");
        }
        catch(Exception $e)
        {
            echo "<span class='dberror'>No se ha podido insertar la información. Por favor verifique que los datos son correctos.</span>";
        }
    }

    /**
     * \brief Inserta un memo a un usuario.
     *
     * @param $sCC Cédula del usuario al que se le hará el memo.
     * @param $sTitle título del memo.
     * @param $sDescription Descripcion del memo.
     *
     */
    public function insertMemo($sCC, $sTitle, $sDescription)
    {
        try
        {
            $this->dbAdapter->fetchRow("SELECT * FROM f_insertmemo('$sCC', '$sTitle', '$sDescription')");
        }
        catch(Exception $e)
        {
            echo "<span class='dberror'>No se ha podido insertar el memorando. Por favor verifique que los datos son correctos.</span>";
        }
    }

    /**
     * \brief Insertamos una noticia.
     *
     * @param $sCCOwner Cédula del usuario que crea la noticia.
     * @param $sTitle Título de la noticia.
     * @param $sHeader Breve descripción de la noticia. Este descripción se mostrará en la lista de noticias.
     * @param $sDescription Descripción o cuerpo de la noticia. Este parámetro es opcional.
     * @param $sImage Ruta a la imágen que se adjuntará en la noticia. Este parámetro es opcional.
     *
     */
    public function insertNews($sCCOwner, $sTitle, $sHeader, $sDescription = '', $sImage = '')
    {
        try
        {
            $cols = "title, header, cc_owner";
            $vals = "'$sTitle', '$sHeader', '$sCCOwner'";

            if($sDescription != "")
            {
                $cols .= ", description";
                $vals .= ", '$sDescription'";
            }

            if($sImage != '')
            {
                $cols .= ", image";
                $vals .= ", lo_import('$sImage')";
            }

            $this->dbAdapter->fetchRow("INSERT INTO tb_news ($cols) VALUES ($vals)");
        }
        catch(Exception $e)
        {
            throw new GDException("No se ha podido insertar la noticia. Por favor verifique que los datos son correctos.", 0, $e);
        }
    }

    /**
     * \brief Insertamos un proyecto en la base de datos.
     *
     * @param $sName Nombre del proyecto.
     * @param $sDescription Descripción del proyecto.
     * @param $sCCClient Cliente a quien está dirigido el proyecto.
     * @param $iType Tipo de proyecto (0 opensource, 1 privado).
     * @param $sImage Dirección donde está la imagen a guardar. Este parámetro es opcional.
     *
     */
    public function insertProyect($sName, $sDescription, $sCCClient, $iType, $sImage = '')
    {
        if($sImage == '')
            $this->dbAdapter->fetchRow("INSERT INTO tb_proyect (name, description, cc_client, id_proyecttype) values ('$sName', '$sDescription', '$sCCClient', $iType)");
        else
            $this->dbAdapter->fetchRow("INSERT INTO tb_proyect (name, description, cc_client, id_proyecttype, image) values ('$sName', '$sDescription', '$sCCClient', $iType, lo_import('$sImage'))");
    }

    /**
     * \brief Obtenemos la lista de los usuarios con el rol de administradores.
     *
     * @return Lista de administradores. Cada registro está ordenado así: [cc, names, lastnames, telephone, movil]
     *
     */
    public function listAdmin()
    {
        return $this->dbAdapter->fetchAll("SELECT * FROM v_admin");
    }

    /**
     * \brief Obtenemos la lista de los usuarios con el rol de cliente.
     *
     * @return Lista de clientes. Cada registro está ordenado así: [cc, names, lastnames, telephone, movil]
     *
     */
    public function listClient()
    {
        return $this->dbAdapter->fetchAll("SELECT * FROM v_client");
    }

    /**
     * \brief Obtenemos la lista de los usuarios con el rol de desarrollador.
     *
     * @return Lista de desarrolladores. Cada registro está ordenado así: [cc, names, lastnames, telephone, movil]
     *
     */
    public function listDeveloper()
    {
        return $this->dbAdapter->fetchAll("SELECT * FROM v_developer");
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
        $r = $this->dbAdapter->fetchAll("SELECT id, title, description, image FROM tb_info");
        foreach($r as $row)
            $this->dbAdapter->fetchRow("SELECT lo_export(".$row['image'].", '".GDPG_PATH."/img/inf/".$row['image']."')");
        return $r;
    }

    /**
     * \brief Obtenemos una lista de noticias. Las imagenes de estas se guardan automaticamente en GDPG_PATH/img/news
     *
     * @param $iPage Número de página de la que se desean conocer las noticias.
     *
     * @return Lista de noticias. Cada registro está ordenado así: [id, title, header, image]
     *
     */
    public function listNews($iPage)
    {
        $iCount = $this->dbAdapter->fetchRow("SELECT COUNT(*) AS c FROM tb_news");
        $iCount = $iCount['c'];

        $nPages = (int)($iCount/10);

        $iLimit = 10;

        if($iPage > $nPages)
            $iLimit = $iCount % 10;

        $iEnd = 10*$iPage;

        $r = $this->dbAdapter->fetchAll("SELECT * FROM (SELECT id, title, header, image FROM tb_news LIMIT $iEnd) AS news ORDER BY id DESC LIMIT $iLimit");

        foreach($r as $row)
            $this->dbAdapter->fetchRow("SELECT lo_export(".$row['image'].", '".GDPG_PATH."/img/news/".$row['image']."')");

        return $r;
    }

    /**
     * \brief Lista los memos activos de un usuario.
     *
     * @param $sCC Cédula del usuario.
     *
     * @return Lista de los memos activos de un usuario. Cada registro está ordenado así: [id, title, description]
     *
     */
    public function listMemos($sCC)
    {
        return $this->dbAdapter->fetchAll("SELECT id, title, description FROM v_memo WHERE cc_owner='$sCC'");
    }

    /**
     * \brief Lista los servicions registrados en el sistema.
     *
     * @return Lista de los servicios registrados en el sistema. Cada registro está ordenado así: [id, name, description]
     *
     */
    public function listServices()
    {
        return $this->dbAdapter->fetchAll("SELECT id, name, description FROM tb_service");
    }

    /**
     * \brief Número de noticias.
     *
     * @return Número de noticias en la base de datos.
     *
     */
    public function newsNumber()
    {
        $iCount = $this->dbAdapter->fetchRow("SELECT COUNT(*) AS c FROM tb_news");
        $iCount = $iCount['c'];;

        $nPages = (int)($iCount/10);
        if(($iCount % 10) > 0)
            return $nPages+1;
        else
            return $nPages;
    }

    /**
     * \brief Obtenemos una noticia.
     *
     * @param $iId Número de identificación de la noticia.
     *
     * @return Información de la noticia organizada así: [title, header, description, image, date]
     *
     */
    public function news($iId)
    {
        return $this->dbAdapter->fetchRow("SELECT title, header, description, image, date FROM tb_news");
    }

    /**
     * \brief Obtenemos los proyectos non-free registrados en el sistema.
     *
     * @return [id, name, description, image]
     *
     */
    public function listProyects($sCCClient = '')
    {
        $aProy = $this->dbAdapter->fetchAll("SELECT id, name, description, image FROM tb_proyect");

        foreach($aProy as $r)
            $this->dbAdapter->fetchRow("SELECT lo_export(".$r['image'].", '".GDPG_PATH."/img/proy/".$r['image']."')");

        return $aProy;
    }

    /**
     *
     * @return [id, name, description, image]
     *
     */
    public function listOpenSourceProyects()
    {
    }

    /**
     * \brief Obtenemos los proyectos non-free registrados en el sistema.
     *
     * @return [id, name, description, image]
     *
     */
    public function listNonFreeProyects($sCCClient = '')
    {
        $aProy = $this->dbAdapter->fetchAll("SELECT id, name, description, image FROM tb_proyect WHERE id_proyecttype=2");

        foreach($aProy as $r)
            $this->dbAdapter->fetchRow("SELECT lo_export(".$r['image'].", '".GDPG_PATH."/img/proy/".$r['image']."')");

        return $aProy;
    }

    /**
     * \brief Obtenemos la información de un proyecto.
     *
     * @param $iIdProyect Número de identificación del proyecto.
     *
     * @return Información del proyecto ordenada así: [id, name, description, type, cc_client]
     *
     */
    public function proyect($iIdProyect)
    {
        return $this->dbAdapter->fetchRow("SELECT id, name, description, id_proyecttype AS type, cc_client FROM tb_proyect WHERE id=$iIdProyect");
    }

    /**
     * \brief Obtenemos la información de un servicio.
     *
     * @param $iIdService Número de identificación del servicio.
     *
     * @return Información del proyecto ordenada así: [id, name, description]
     *
     */
    public function service($iIdService)
    {
        return $this->dbAdapter->fetchRow("SELECT id, name, description FROM tb_service WHERE id=$iIdService");
    }

    /**
     * \brief Actualiza una cuenta de administrador en la base de datos.
     *
     * @param $sCC Cédula del administrador.
     * @param $sNames Nombres del administrador.
     * @param $sLastNames Apellidos del administrador.
     * @param $sTelephone Teléfono del administrador.
     * @param $sMovil Celular del administrador.
     * @param $sImage Ruta a la imagen donde está el administrador. Este parámetro es opcional.
     *
     */
    public function updateAdmin($sCC, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage = '')
    {
        if($sImage == '')
            $this->dbAdapter->fetchRow("UPDATE tb_user SET names='$sNames', lastnames='$sLastNames', telephone='$sTelephone', movil='$sMovil' WHERE cc='$sCC'");
        else
        {
            $image = file_get_contents($sImage);
            $image = bin2hex($image);
            $this->dbAdapter->fetchRow("UPDATE tb_user SET image=decode('{$image}', 'hex') WHERE cc='$sCC'");
        }
    }

    /**
     * \brief Actualiza una cuenta de cliente en la base de datos.
     *
     * @param $sCC Cédula del cliente.
     * @param $sNames Nombres del cliente.
     * @param $sLastNames Apellidos del cliente.
     * @param $sTelephone Teléfono del cliente.
     * @param $sMovil Celular del cliente.
     * @param $sImage Ruta a la imagen donde está el cliente. Este parámetro es opcional.
     *
     */
    public function updateClient($sCC, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage = '')
    {
        $this->dbAdapter->fetchRow("SELECT * FROM f_updateuser('$sCC', '$sNames', '$sLastNames', '$sTelephone', '$sMovil', '$sImage')");
    }

    /**
     * \brief Actualiza una cuenta de programador en la base de datos.
     *
     * @param $sCC cédula del programador.
     * @param $sNames nombres del programador.
     * @param $sLastNames apellidos del programador.
     * @param $sTelephone teléfono del programador.
     * @param $sMovil celular del programador.
     * @param $sImage ruta a la imagen donde está el programador. este parámetro es opcional.
     *
     */
    public function updateDeveloper($sCC, $sNames, $sLastNames, $sTelephone, $sMovil, $sImage = '')
    {
        $this->dbAdapter->fetchRow("SELECT * FROM f_updateuser('$sCC', '$sNames', '$sLastNames', '$sTelephone', '$sMovil', '$sImage')");
    }

    /**
     * \brief Actualiza información empresarial.
     *
     * @param $iId Número de identificación de la información.
     * @param $sTitle Titulo de la información, ejemplo: Misión.
     * @param $sDescription Descripción o cuerpo de la información.
     * @param $sImage Ruta una imágen representativa de la información. Este campo es opcional.
     *
     */
    public function updateInfo($iId, $sTitle, $sDescription, $sImage = '')
    {
        if($sImage == '')
            $this->dbAdapter->fetchRow("UPDATE tb_info SET title='$sTitle', description='$sDescription' WHERE id=$iId");
        else
            $this->dbAdapter->fetchRow("UPDATE tb_info SET title='$sTitle', description='$sDescription', image=lo_import('$sImage') WHERE id=$iId");
    }

    /**
     * \brief Actualiza un memo.
     *
     * @param $iId Número de identificación del memo.
     * @param $sTitle Titulo del memo.
     * @param $sDescription Descripción del memo.
     *
     */
    public function updateMemo($iId, $sTitle, $sDescription)
    {
        $this->dbAdapter->fetchRow("UPDATE tb_memo SET title='$sTitle', description='$sDescription' WHERE id=$iId");
    }

    /**
     * \brief Actualiza una noticia.
     *
     * @param $iId Número de identificación de la noticia.
     * @param $sTitle Titulo de la noticia.
     * @param $sHeader Breve descripción de la noticia. Este descripción se mostrará en la lista de noticias.
     * @param $sDescription Cuerpo de la noticia. Este parámetro es opcional.
     * @param $sImage Ruta a la imagen. Este parámetro es opcional.
     *
     */
    public function updateNews($iId, $sTitle, $sHeader, $sDescription = '', $sImage = '')
    {
        try
        {
            $this->dbAdapter->fetchRow("UPDATE tb_news SET title='$sTitle', header='$sHeader'".(($sDescription=="")?"":", description='$sDescription'").(($sImage=="")?"":", image=lo_import('$sImage')")." WHERE id=$iId");
        }
        catch(Exception $e)
        {
            throw new GDException("No se ha podido actualizar la noticia. Por favor verifique que los datos son correctos.", 0, $e);
        }
    }

    /**
     * \brief Cambia la contraseña de un usuario.
     *
     * @param $sCC Cédula del usuario al que cambiaremos la contraseña.
     * @param $sNewPassword Nueva contraseña del usuario. La contraseña deberá ser pasada en encriptada, con el algoritmo de encriptación SHA1.
     *
     */
    public function updatePassword($sCC, $sNewPassword)
    {
        $this->dbAdapter->fetchRow("UPDATE tb_user SET password='$sNewPassword' WHERE cc='$sCC'");
    }

    /**
     * \brief Actualizamos un proyecto en la base de datos.
     *
     * @param $iIdProyect Número de identificación del proyecto a actualizar.
     * @param $sName Nombre del proyecto.
     * @param $sDescription Descripción del proyecto.
     * @param $sCCClient Cliente a quien está dirigido el proyecto.
     * @param $iType Tipo de proyecto (0 opensource, 1 privado).
     * @param $sImage Dirección donde está la imagen a guardar. Este parámetro es opcional.
     *
     */
    public function updateProyect($iIdProyect, $sName, $sDescription, $sCCClient, $iType, $sImage = '')
    {

        if($sImage == '')
            $this->dbAdapter->fetchRow("UPDATE tb_proyect SET name='$sName', description='$sDescription', cc_client='$sCCClient', id_proyecttype=$iType WHERE id=$iIdProyect");
        else
            $this->dbAdapter->fetchRow("UPDATE tb_proyect SET name='$sName', description='$sDescription', cc_client='$sCCClient', id_proyecttype=$iType, image=lo_import('$sImage') WHERE id=$iIdProyect");
    }

    /**
     * \brief Actualizamos un servicio en la base de datos.
     *
     * @param $iIdService Número de identificación del servicio a actualizar.
     * @param $sName Nombre del servicio.
     * @param $sDescription Descripción del servicio.
     *
     */
    public function updateService($iIdService, $sName, $sDescription)
    {
        $this->dbAdapter->fetchRow("UPDATE tb_service SET name='$sName', description='$sDescription' WHERE id=$iIdService");
    }

    /**
     * \brief Obtenemos los datos de un usuario.
     *
     * @param $sCC Cédula del usuario.
     *
     * @return Datos del usuario ordenados así: [cc, names, lastnames, telephone, movil, id_usertype]
     *
     */
    public function user($sCC)
    {
        return $this->dbAdapter->fetchRow("SELECT cc, names, lastnames, telephone, movil, id_usertype FROM tb_user WHERE activated=TRUE AND cc='$sCC'");
    }

    /**
     * \brief Obtenemos el tipo de usuario (nombre) con base en el identificador.
     * Cada tipo de usuario tiene un nombre y un identificador único.
     * Ejemplo: Nombre 'Desarrollador', identificador 3
     * Este método retorna el nombre asociado a un identificador de tipo.
     *
     * @return Nombre asociado a un identificador de tipo.
     *
     */
    public function _userType($iId)
    {
        $r = $this->dbAdapter->fetchRow("SELECT name FROM tb_usertype WHERE id=$iId");
        return $r['name'];
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
