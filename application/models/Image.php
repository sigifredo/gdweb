<?php

class Image
{
    protected $_content = null;
    protected $_type = null;
    protected $_sName = null;

    function __construct($sName, $content = null, $type = null)
    {
        if($content == null || $type == null)
        {
            $extension = "";
            $archivo = "";

            for($i = 0; $i < strlen($sName); $i++)
            {
                if($sName[$i] == '/')
                    $archivo = "";
                else
                {
                    if($sName[$i] == '.')
                        $extension = "";
                    else
                        $extension .= $sName[$i];
                    $archivo .= $sName[$i];
                }
            }

            $this->_sName = $archivo;
            $this->_content = file_get_contents($sName);
            $this->_type = $extension;
        }
        else
        {
            $this->_sName = $sName;
            $this->_content = $content;
            $this->_type = $type;
        }
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
