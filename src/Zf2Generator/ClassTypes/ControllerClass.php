<?php

namespace Zf2Generator\ClassTypes;

class ControllerClass extends AbstractClassType
{

    function getServiceLocatorSectionKey()
    {
        return "controllers";
    }
    
    function getServiceLocatorKey($fqClass)
    {
        return substr($fqClass,0,0-\strlen("Controller"));
    }
}
