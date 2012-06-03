<?php

class Version
{
    function authors()
    {
        $aAuthors = array(
                         array("Name" => "Sigifredo Escobar Gómez", "" => "Designer, Developer"),
                         array("Name" => "Marisol Correa Henao", "" => "Developer")
                         );
        return $aAuthors;
    }

    function version()
    {
        return "1.alfa";
    }

    function releaseDate()
    {
    }

    function dbVersion()
    {
    }
}
