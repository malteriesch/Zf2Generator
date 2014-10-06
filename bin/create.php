<?php
chdir(dirname(dirname(dirname(dirname(__DIR__)))));

// Setup autoloading
require 'init_autoloader.php';

$serviceManager = new \Zend\ServiceManager\ServiceManager(new \Zend\Mvc\Service\ServiceManagerConfig() );
$serviceManager->setService('ApplicationConfig', include 'config/application.config.php');
$moduleManager = $serviceManager->get('ModuleManager');
$moduleManager->loadModules();

$application = new \Zf2Generator\Application();

exit($application->run());




