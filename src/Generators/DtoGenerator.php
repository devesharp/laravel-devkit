<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\TemplateData;
use Devesharp\Generators\Common\TemplateGenerator;

class DtoGenerator extends TemplateGenerator
{
    public string $resourceType = 'dto';

    function loadImports(): void {
        $this->templateData->addImport('Devesharp\Patterns\Dto\AbstractDto');
        $this->templateData->addImport('Devesharp\Patterns\Dto\Rule');
        if(@$this->additionalData['template'] == 'search') {
            $this->templateData->addImport('Devesharp\Patterns\Dto\Templates\SearchTemplateDto');
        }
        if(@$this->additionalData['template'] == 'delete') {
            $this->templateData->addImport('Devesharp\Patterns\Dto\Templates\ActionManyTemplateDto');
        }
        if(@$this->additionalData['template'] == 'update') {
            $namespaceApp = $this->getNamespace();
            $resourceName = $this->resourceName;
            $this->templateData->addImport("$namespaceApp\Create{$resourceName}Dto");
        }
    }

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Dto/dto-default';
    }

    public function getData(): array
    {
        return [];
    }

    public function setTemplateData(TemplateData $templateData, $additionalData = []): void
    {
        $this->additionalData = $additionalData;

        parent::setTemplateData($templateData);
    }

    public function getFileName()
    {
        if (!empty($this->additionalData['template'])) {
            if ($this->additionalData['template'] == 'create') {
                return 'Create' . parent::getFileName();
            }else if ($this->additionalData['template'] == 'update') {
                return 'Update' . parent::getFileName();
            }else if ($this->additionalData['template'] == 'delete') {
                return 'Delete' . parent::getFileName();
            }else if ($this->additionalData['template'] == 'search') {
                return 'Search' . parent::getFileName();
            }
        }

        return parent::getFileName();
    }
}


//use Devesharp\Patterns\Dto\AbstractDto;
//use Devesharp\Patterns\Dto\Rule;
//@if(@$options['template'] == 'search')
//use Devesharp\Patterns\Dto\Templates\SearchTemplateDto;
//@endif
//@if(@$options['template'] == 'delete')
//use Devesharp\Patterns\Dto\Templates\ActionManyTemplateDto;
//@endif
//@if(@$options['template'] == 'update')
//use {{ $namespaceApp }}\Create{{ $resourceName }}Dto;
//@endif