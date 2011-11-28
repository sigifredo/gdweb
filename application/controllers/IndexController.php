<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
	$this->view->headTitle("Inicio");
        $sql = new Application_Model_SQL();
        $news = $sql->listNews();
        $i=1;
        echo "<div id='news'>";
        foreach($news as $line)
        {
            if($i%2 == 0) echo "<article class='artr'>";
            else echo "<article class='artl'>";
            echo "<header class='title'>".$line['title']."</header>";
            echo "<p>".$line['description']."</p>";
            echo "</article>";
            $i++;
        }
        echo "</div>";
    }
}
