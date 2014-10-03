<?php

namespace @MODULE@\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class @CONTROLLER_NAME@ControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllers)
    {
        $services = $controllers->getServiceLocator();
        return new \@MODULE@\Controller\@CONTROLLER_NAME@Controller();
    }
}
