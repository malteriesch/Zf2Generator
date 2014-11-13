<?php
namespace Zf2Generator\ClassTypes;

abstract class AbstractClassType implements ZFClassInterface
{
    
    static function create($type)
    {
        $class = 'Zf2Generator\\ClassTypes\\'.$type."Class";
        return new $class();
    }
    
    function getServiceLocatorKey($fqClass)
    {
        return $fqClass;
    }
   
}
