<?php

class Image
{
    protected $_data = null;

    function __construct($sPath, $bByte = false)
    {
        if($bByte)
            $this->_data = pg_unescape_bytea($sPath);
        else
            $this->_data = file_get_contents($sPath);
    }

    public function bytes()
    {
        return pg_escape_bytea($this->_data);
    }
}
