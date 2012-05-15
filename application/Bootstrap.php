<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoloader()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('App_')->setFallbackAutoloader(true);
 
        $resourceAutoloader = new Zend_Loader_Autoloader_Resource(
            array(
                'basePath' => APPLICATION_PATH,
                'namespace' => 'App',
                'resourceTypes' => array(
                    'form' => array('path' => 'forms/', 'namespace' => 'Form'),
                    'model' => array('path' => 'models/', 'namespace' => 'Model')
                )
            )
        );
    }
}

