<?php

namespace Zf2Generator;

class Psr2ClassInfo
{

    protected $fqClassPath;

    function __construct($fqClassPath)
    {
        $this->fqClassPath = $this->cleanNamespace($fqClassPath);
        $this->parts       = explode('\\',$this->fqClassPath );
        $this->phpPath     = str_replace("\\","/",$this->fqClassPath).".php";
        $this->module      = $this->parts[0];
        $this->innerNamespace = $this->calcInnerNamespace($this->parts);
        $this->namespace = $this->calcNamespace($this->parts);
    }

    protected function cleanNamespace($toClean)
    {
        $seperator = preg_quote('\\');
        return trim(preg_replace("/$seperator$seperator*/", $seperator, $toClean), '\\');
    }

    protected function cleanPath($toClean)
    {
        return str_replace("//", "/", $toClean);
    }

    function getPhpPath()
    {
        return $this->phpPath;
    }
    
    function getFullyQualifiedClass()
    {
        return $this->fqClassPath;
    }
    protected function calcInnerNamespace($parts) 
    {
        array_pop($parts);
        array_shift($parts);
        return implode('\\',$parts);
    }
    protected function calcNamespace($parts) 
    {
        array_pop($parts);
        return implode('\\',$parts);
    }
    function getModule()
    {
        return $this->module;
    }
    function getShortClassname()
    {
        return $this->parts[count($this->parts)-1];
    }
    function getInnerNamespace()
    {
        return $this->innerNamespace;
    }
    function getNamespace()
    {
        return $this->namespace;
    }
    
    function getFactoryClassInfo()
    {
        return new self($this->getModule().'\\Factory\\'.$this->getInnerNamespace().'\\'.$this->getShortClassname().'Factory');
    }
    
}
