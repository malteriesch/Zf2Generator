<?php

namespace @NAMESPACE@;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class @CLASS@ implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        return new @FULL_CLASS_PATH@();
    }
}
