<?php

namespace Zf2Generator\Generator;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface;
use Zend\Console\Console;
use ZF\Console\Route;
use ZF\Configuration\ConfigResource;

class Application extends \ZF\Console\Application {

    function __construct() {
        
       parent::__construct("Code Creation", "1.0", $this->getConfiguration(), Console::getInstance());
       
    }

    function getConfiguration() {
        return include 'tools/config/routes.php';
    }

    public function createRoute(Route $route, AdapterInterface $console) {

        $moduleName       = $route->getMatchedParam('module');
        $routeNameAndPath = $route->getMatchedParam('path');
        $controllerName   = $route->getMatchedParam('controller');
        $actionName       = $route->getMatchedParam('action');
        $separator        = '\\';
        $patch            = array(
            'router' => array(
                'routes' => array(
                    $routeNameAndPath => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => $routeNameAndPath,
                            'defaults' => array(
                                'controller' => $moduleName . $separator . "Controller" . $separator . $controllerName,
                                'action'     => $actionName,
                            ),
                        ),
                    )
                )
            )
        );
        $configResource   = new ConfigResource(array(), "module/$moduleName/config/module.config.php", new Zend\Config\Writer\PhpArray());
        $configResource->patch($patch);
    }

    public function createController(Route $route, AdapterInterface $console) {
        $moduleName     = $route->getMatchedParam('module');
        $controllerName = $route->getMatchedParam('name');


        $patch = array(
            'controllers' => array(
                'factories' => array(
                    "$moduleName/Controller/$controllerName" => "$moduleName\Factory\Controller\\{$controllerName}ControllerFactory"
                ),
            ),
        );

        $configResource = new ConfigResource(array(), "module/$moduleName/config/module.config.php", new \Zend\Config\Writer\PhpArray());
        $configResource->patch($patch);

        $generator = new Generator($moduleName);
        $generator->processSourceTemplate("ControllerFactory.php.tpl", "Factory/Controller/{$controllerName}ControllerFactory.php", ['MODULE' => $moduleName, 'CONTROLLER_NAME' => $controllerName]);
        $generator->processSourceTemplate("Controller.php.tpl", "Controller/{$controllerName}Controller.php", ['MODULE' => $moduleName, 'CONTROLLER_NAME' => $controllerName]);

        $this->echoMessages($generator->getMessages());
    }

    public function createModule(Route $route, AdapterInterface $console) {
        $moduleName = $route->getMatchedParam('name');
        $generator   = new Generator($moduleName);
        $generator->processTemplate("Module.php.tpl", 'Module.php', ['MODULE' => $moduleName]);
        $generator->processTemplate("module.config.php.tpl", 'config/module.config.php', []);
        $this->echoMessages($generator->getMessages());
    }
    
    public function createAction(Route $route, AdapterInterface $console) {
        $generator   = new Generator(null);
        $action = $route->getMatchedParam('action');
        $generator->insertMethodIntoClass($route->getMatchedParam('controller_class'),$action);
        
        $this->echoMessages($generator->getMessages());
    }
    
    public function createView(Route $route, AdapterInterface $console) {
        $generator   = new Generator($route->getMatchedParam('module'));
        $moduleName       = strtolower($route->getMatchedParam('module'));
        $controllerName   = strtolower($route->getMatchedParam('controller'));
        $actionName       = strtolower($route->getMatchedParam('action'));
        $viewFile = "view/$moduleName/$controllerName/$actionName.phtml";
        $generator->processTemplate("view.phtml.tpl", $viewFile, []);
    }

    protected function echoMessages($messages)
    {
        foreach ($messages as $message) {
            $this->console->writeLine($message, ColorInterface::LIGHT_RED);
        }
    }
}
