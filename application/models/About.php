<?php

class About
{
    public function authors()
    {
        $aAuthors = array(
                         array("Name" => "Sigifredo Escobar Gómez", "Task" => "Diseñador, desarrollador"),
                         array("Name" => "Marisol Correa Henao", "Task" => "Desarrolladora")
                         );
        return $aAuthors;
    }

    public function version()
    {
        return "2.3.3dev";
    }

    public function releaseDate()
    {
        return new Date(19, 6, 2012);
    }
}
