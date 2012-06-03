<?php

class About
{
    public function authors()
    {
        $aAuthors = array(
                         array("Name" => "Sigifredo Escobar Gómez", "Task" => "Designer, Developer"),
                         array("Name" => "Marisol Correa Henao", "Task" => "Developer")
                         );
        return $aAuthors;
    }

    public function version()
    {
        return "1.alfa";
    }

    public function releaseDate()
    {
    }

    public function dbVersion()
    {
    }
}
