<?php

namespace Zf2Generator\ClassTypes;

class ServiceClass extends AbstractClassType
{

    function getServiceLocatorSectionKey()
    {
        return "service_manager";
    }

}
