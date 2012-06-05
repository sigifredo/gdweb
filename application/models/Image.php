<?php

class Image
{
    protected $_content = null;
    protected $_type = null;
    protected $_sName = null;

    function __construct($sName, $content, $type)
    {
        $this->_sName = $sName;
        $this->_content = $content;
        $this->_type = $type;
    }

    public function name()
    {
        return $this->_sName;
    }

    public function content()
    {
        return $this->_content;
    }

    public function type()
    {
        return $this->_type;
    }

}
