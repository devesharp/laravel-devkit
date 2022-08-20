<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakePresenter extends GeneratorBase
{
    protected $name = 'ds:presenter';

    protected $description = 'Create a new presenter';

    protected $type = 'Presenter';

    protected string $folder = 'Presenters';

    protected function getStub()
    {
        return  __DIR__ . '/Stubs/presenter.stub';
    }
}