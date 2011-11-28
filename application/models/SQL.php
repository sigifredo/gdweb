<?php

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
     * \brief Obtenemos una lista de noticias.
     *
     * @return Lista de noticias. Cada registro está ordenado así: [id, title, description]
     *
     */
    public function listNews()
    {
        return $this->dbAdapter->fetchAll("SELECT id, title, description FROM tb_news");
    }

}