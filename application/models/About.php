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
        return "3.0.2";
    }

    public function releaseDate()
    {
        return new Date(14, 8, 2012);
    }
}
