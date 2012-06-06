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
$direccion = "/home/direccion.php";
$extension = "";
$archivo = "";

for($i = 0; $i < strlen($direccion); $i++)
{
    if($direccion[$i] == '/')
        $archivo = "";
    else
    {
        if($direccion[$i] == '.')
            $extension = "";
        else
            $extension .= $direccion[$i];
        $archivo .= $direccion[$i];
    }
}

echo $extension."<br>";
echo $archivo;
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
