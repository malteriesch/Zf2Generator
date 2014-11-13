<?php

class Psr2ClassInfoTest extends \TestDbAcleTests\TestDbAcle\BaseTestCase
{

    function test_inistantiating_CleansUp()
    {

        $psr2Info = new \Zf2Generator\Psr2ClassInfo('\\FooModule\Controller\\\\IndexController\\');
        $this->assertEquals('FooModule\Controller\IndexController', $psr2Info->getFullyQualifiedClass());
    }

    function test_getPhpPath()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\IndexController');
        $this->assertEquals('FooModule/Controller/IndexController.php', $psr2Info->getPhpPath());
    }

    function test_getFullyQualifiedClass()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\IndexController');
        $this->assertEquals('FooModule\Controller\IndexController', $psr2Info->getFullyQualifiedClass());
    }

    function test_getModule()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\IndexController');
        $this->assertEquals('FooModule', $psr2Info->getModule());
    }
    
    function test_getShortClassname()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\SubController\IndexController');
        $this->assertEquals('IndexController', $psr2Info->getShortClassname());
    }

    function test_getInnerNamespace()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\IndexController');
        $this->assertEquals('Controller', $psr2Info->getInnerNamespace());
    }
    
    
    function test_getInnerNamespace_Complex()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\SubController\IndexController');
        $this->assertEquals('Controller\SubController', $psr2Info->getInnerNamespace());
    }
    function test_getNamespace()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\IndexController');
        $this->assertEquals('FooModule\Controller', $psr2Info->getNamespace());
    }
    
    function test_getNamespace_Complex()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\SubController\IndexController');
        $this->assertEquals('FooModule\Controller\SubController', $psr2Info->getNamespace());
    }

   
    function test_getFactoryClassInfo()
    {
        $psr2Info = new \Zf2Generator\Psr2ClassInfo('FooModule\Controller\SubController\IndexController');
        $this->assertEquals('FooModule/Factory/Controller/SubController/IndexControllerFactory.php', $psr2Info->getFactoryClassInfo()->getPhpPath());
    }
}
