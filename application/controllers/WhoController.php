<?php

class WhoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->headTitle("¿Quiénes somos?");
        $sql = new Application_Model_SQL();

        $info = $sql->listInformation();
        echo "<div id='information'>";
        foreach($info as $line)
        {
            echo "<article>";
            echo "<header class='title'>".$line['title']."</header>";
            echo "<p>".$line['description']."</p>";
            echo "</article>";
        }
        echo "</div>";
    }

}
