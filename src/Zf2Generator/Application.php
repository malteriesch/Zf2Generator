<?php

namespace Zf2Generator;

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
        return include __DIR__.'/../../config/routes.php';
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
    
    protected function lowercaseFirst($subject)
    {
        $subject[0] = strtolower($subject[0]);
        return $subject;
    }
    
    protected function getLastFromNamespace($subject)
    {
        $namespaces = explode('\\', $subject);
        return array_pop($namespaces);
    }
    
    protected function getVarnameFromClassname($classname) {
        
        return $this->lowercaseFirst($this->getLastFromNamespace($classname));
        
    }
            
    public function createMember(Route $route, AdapterInterface $console) {
        
        $targetClass = $route->getMatchedParam('target_class');
        $memberClass = $route->getMatchedParam('member_class');
        $memberClassShort = $this->getLastFromNamespace($route->getMatchedParam('member_class'));
        
        $memberName = $route->getMatchedParam('member_name');
        if(!$memberName) {
            $memberName = $this->getVarnameFromClassname($memberClass);
        }
        $options = $route->getMatchedParam('options');
        
        $addSetter = in_array('setter', $options);
        $addGetter = in_array('getter', $options);
        $addToConstructor = in_array('constructor', $options);
        
        $generator   = new Generator(null);
        $generator->insertPropertyIntoClass($targetClass, $memberName, $memberClass);
        
        $method ="    /**\n";
        $method.="     *\n";
        $method.="     * @return \\$memberClass\n";
        $method.="     */\n";
        
     
     
        $method.="    public function get$memberClassShort()\n";
        $method.="    {\n";
        $method.="        if (!\$this->$memberName) {\n";
        $method.="            \$this->set$memberClassShort(new \\$memberClass());\n";
        $method.="        }\n";
        $method.="        return \$this->$memberName;\n";
        $method.="    }\n\n";
        
        $method.="    /**\n";
        $method.="     *\n";
        $method.="     * @param \\$memberClass \$$memberName\n";
        $method.="     */\n";
        
        $method.="    public function set$memberClassShort(\\$memberClass \$$memberName)\n";
        $method.="    {\n";
        $method.="        \$this->$memberName = \$$memberName;\n";
        $method.="    }";
        $generator->insertMethodIntoClass($targetClass, $method);
        
        $this->echoMessages($generator->getMessages());
    }
}
