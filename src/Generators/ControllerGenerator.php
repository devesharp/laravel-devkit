<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\TemplateGenerator;

class ControllerGenerator extends TemplateGenerator
{
    public string $resourceType = 'controller';

    function renderRoutes() {
        return view('devesharp-generators::Routes/route-default', [...$this->getRootData(), ...$this->getData()])->render();
    }

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\Patterns\Controller\ControllerBase');
        $this->templateData->addImport($this->templateData->dtoNamespace . "\Create" . $this->templateData->resourceName . "Dto");
        $this->templateData->addImport($this->templateData->dtoNamespace . "\Search" . $this->templateData->resourceName . "Dto");
        $this->templateData->addImport($this->templateData->dtoNamespace . "\Update" . $this->templateData->resourceName . "Dto");
        if ($this->templateData->withTransformerInterface) {
            $this->templateData->addImport($this->templateData->transformerInterfaceNamespace . "\\" . $this->templateData->resourceName . "TransformerType");
        }
    }

//    function handle() {
//
//    }

//    public function generate()
//    {
//        parent::generate();
//
//        $render = $this->renderRoutes();
//        $file = $this->fileSystem->readFile($this->config->apiRoutesPath);
//        $baseFileName = str_replace(base_path(''), '', $file);
//
//        if (strpos($file, $render) == false) {
////            file_put_contents($this->config->apiRoutesPath, $render, FILE_APPEND);
//            $file .= "\n";
//            $file .= $render;
//            $this->fileSystem->writeFile($this->config->apiRoutesPath, $file);
//            $this->infoEditFile($baseFileName);
//        }
//    }

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Controller/controller';
    }

    public function getData(): array
    {
        return [];
    }
}
