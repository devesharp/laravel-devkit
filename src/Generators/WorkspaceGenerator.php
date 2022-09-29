<?php

namespace Devesharp\Generators;

use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\FileSystem;
use Devesharp\Generators\Common\TemplateData;

class WorkspaceGenerator
{
    public array $data = [];

    function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    function generate()
    {
        $this->data['file_template'] = $this->data['file_template'] ?? null;

        $workspaceFile = file_get_contents($this->data['file_template']);
        $workspace = \yaml_parse($workspaceFile);


        $modules = [];
        foreach ($workspace['modules'] as $module) {
            $moduleFile = file_get_contents(dirname($this->data['file_template']) . '/' . $module['source']);
            $moduleData = \yaml_parse($moduleFile);
            $moduleData['file_template'] = dirname($this->data['file_template']) . '/' . $module['source'];
            $modules[] = $moduleData;
        }

        foreach ($modules as $module) {
            /** @var ModuleGenerator $moduleGenerator */
            $moduleGenerator = app(ModuleGenerator::class);
            $moduleGenerator->setTemplateData(TemplateData::makeByFile($module['file_template']));
            $moduleGenerator->generate('all');
        }

        var_dump(app(FileSystem::class)->getFiles());
//            /** @var ModuleGenerator $moduleGenerator */
//            $moduleGenerator = app(ModuleGenerator::class);
//            $moduleGenerator->setData([
//                'module' => $module['module'] ?? $module['name'],
//                'name' => $module['name'],
//                'file_template' => $module['file_template'],
//                'withController' => isset($module['layers']['withController']) ? $module['layers']['withController'] : true,
//                'withDto' => isset($module['layers']['withDto']) ? $module['layers']['withDto'] : true,
//                'withService' => isset($module['layers']['withService']) ? $module['layers']['withService'] : true,
//                'withFactory' => isset($module['layers']['withFactory']) ? $module['layers']['withFactory'] : true,
//                'withMigration' => isset($module['layers']['withMigration']) ? $module['layers']['withMigration'] : true,
//                'withModel' => isset($module['layers']['withModel']) ? $module['layers']['withModel'] : true,
//                'withPolicy' => isset($module['layers']['withPolicy']) ? $module['layers']['withPolicy'] : true,
//                'withPresenter' => isset($module['layers']['withPresenter']) ? $module['layers']['withPresenter'] : true,
//                'withRepository' => isset($module['layers']['withRepository']) ? $module['layers']['withRepository'] : true,
//                'withRouteDocs' => isset($module['layers']['withRouteDocs']) ? $module['layers']['withRouteDocs'] : true,
//                'withTransformerInterface' => isset($module['layers']['withTransformerInterface']) ? $module['layers']['withTransformerInterface'] : true,
//                'withTransformer' => isset($module['layers']['withTransformer']) ? $module['layers']['withTransformer'] : true,
//                'withTestRoute' => isset($module['layers']['withTestRoute']) ? $module['layers']['withTestRoute'] : true,
//                'withTestUnit' => isset($module['layers']['withTestUnit']) ? $module['layers']['withTestUnit'] : true,
//            ]);
//            $moduleGenerator->generate('all', []);
//        }


//        /** @var ModuleGenerator $moduleGenerator */
//        $moduleGenerator = app(ModuleGenerator::class);
//        $moduleGenerator->setData([
//            'moduleName' => $this->data['moduleName'],
//            'withController' => $this->data['withController'],
//            'withDto' => $this->data['withDto'],
//            'withService' => $this->data['withService'],
//            'withFactory' => $this->data['withFactory'],
//            'withModel' => $this->data['withModel'],
//            'withPolicy' => $this->data['withPolicy'],
//            'withPresenter' => $this->data['withPresenter'],
//            'withRepository' => $this->data['withRepository'],
//            'withRouteDocs' => $this->data['withRouteDocs'],
//            'withTransformerInterface' => $this->data['withTransformerInterface'],
//            'withTransformer' => $this->data['withTransformer'],
//            'withTestRoute' => $this->data['withTestRoute'],
//            'withTestUnit' => $this->data['withTestUnit'],
//        ]);
//        $moduleGenerator->generate('all');
    }
}
