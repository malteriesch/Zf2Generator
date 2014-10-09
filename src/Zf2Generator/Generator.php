<?php
namespace Zf2Generator;


class Generator 
{
    protected $module;
    protected $templatePath;
    protected $basePath;
    protected $messages = [];
    
    function __construct($module = null, $templatePath="tools/templates") {
        $this->module       = $module;
        $this->templatePath = $templatePath;
        $this->basePath     = "module/$module/";
    }
    
    function createFolder($path)
    {
        if(file_exists($path)){
            return;
        }
        mkdir($path,0777,true);
    }
    
    function ensureFolder($path='')
    {
        $this->createFolder("{$this->basePath}/$path");
    }
    
    function ensureSourceFolder($path='')
    {
        $this->ensureFolder("src/{$this->module}/$path");
    }
    
    protected function addMessage($message)
    {
        $this->messages[] = $message;
    }
    

    function processTemplateIntoString($source, array $replacements)
    {
        $template = file_get_contents("{$this->templatePath}/$source");
        foreach($replacements as $templateId=>$replacement ){
            $template = str_replace("@$templateId@", $replacement, $template);
        }
        return $template;
    }

    function processTemplate($source, $destination, array $replacements)
    {
        $destinationFullPath = "{$this->basePath}$destination";
        if(file_exists($destinationFullPath)){
            $this->addMessage("$destination already exists. Skipping.");
            return;
        }
        
        $template = file_get_contents("{$this->templatePath}/$source");
        $template = str_replace(".tpl","",$template);
        foreach($replacements as $templateId=>$replacement ){
            $template = str_replace("@$templateId@", $replacement, $template);
        }
        
        $this->createFolder(dirname($destinationFullPath));
        file_put_contents($destinationFullPath,$template);
    }
    
    function processSourceTemplate($source, $destination, array $replacements)
    {
        $this->processTemplate($source, "src/{$this->module}/$destination", $replacements);
    }
    
    function getMessages()
    {
        return $this->messages;
    }
    //rnemae to insertAtClassEnd
    function insertMethodIntoClass($class, $content)
    {
        $reflectionClass = new \ReflectionClass($class);
        $this->insertIntoFile($reflectionClass->getFileName(), $reflectionClass->getEndLine(), $content);
    }
    
    function insertPropertyIntoClass($class, $name, $type)
    {
        $reflectionClass = new \ReflectionClass($class);
        $content = '    /* @var $'.$name.' '.$type.' */';
        $content.="\n";
        $content.='    protected $'.$name.';';
        $this->insertIntoFile($reflectionClass->getFileName(), $reflectionClass->getStartLine()+2, $content);
    }
    
    function insertAction($class, $action)
    {
        $this->insertMethodIntoClass($class, $this->processTemplateIntoString("Action.php.tpl",["ACTION"=>$action]));
    }
    
    function insertIntoFile($file, $lineNumber, $content)
    {
        $fileLines = file($file);
        array_splice($fileLines,$lineNumber-1,0, array($content,"\n"));
        file_put_contents($file,implode($fileLines));
    }
}