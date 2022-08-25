<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class MakeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ds:all';

    protected $description = 'Command description';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::REQUIRED, 'The name of the service.'],
            ['name', InputArgument::OPTIONAL, 'The name of the service.']
        ];
    }

    protected function getOptions()
    {
        return [
            ['all', 'a', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create all resources'],
            ['service', 's', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only service'],
            ['policy', 'p', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only policy'],
            ['validator', 'l', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only validator'],
            ['transformer', 't', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only transformer'],
            ['repository', 'r', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only repository'],
            ['controller', 'c', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only controller'],
            ['route', 'o', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only route'],
            ['presenter', 'i', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only presenter'],
            ['unit-test', 'u', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only unit test'],
            ['route-test', 'b', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only route test'],
        ];
    }

    public function handle()
    {
        $all = $this->option('all');

//        $namePtBr = $this->ask('Nome em português em plural do módulo (Para usar usado nas documentações): ');
        $namePtBr = 'sdsd';


        if ($all || $this->confirm('create route?')) {
            $this->callSilent('ds:route', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module') ]);
        }

        if ($all || $this->confirm('create dto?')) {
            $this->callSilent('ds:dto', [ 'module' => $this->argument('module'), 'name' => 'Create' . ($this->argument('name') ?? $this->argument('module')) ]);
            $this->callSilent('ds:dto', [ 'module' => $this->argument('module'), 'name' => 'Update' . ($this->argument('name') ?? $this->argument('module')), '--type' => 'update' ]);
            $this->callSilent('ds:dto', [ 'module' => $this->argument('module'), 'name' => 'Search' . ($this->argument('name') ?? $this->argument('module')), '--type' => 'search' ]);
            $this->callSilent('ds:dto', [ 'module' => $this->argument('module'), 'name' => 'Delete' . ($this->argument('name') ?? $this->argument('module')), '--type' => 'delete' ]);
        }

        if ($all || $this->confirm('create service?')) {
            $this->callSilent('ds:service', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module') ]);
        }

        if ($all || $this->confirm('create presenter?')) {
            $this->callSilent('ds:presenter', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module') ]);
        }

        if ($all || $this->confirm('create transformer?')) {
            $this->callSilent('ds:transformer', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module') ]);
        }

        if ($all || $this->confirm('create policy?')) {
            $this->callSilent('ds:policy', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module') ]);
        }

        if ($all || $this->confirm('create repository?')) {
            $this->callSilent('ds:repository', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module'), '--all' => true ]);
        }

        if ($all || $this->confirm('create controller?')) {
            $this->callSilent('ds:controller', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module') ]);
        }

        if ($all || $this->option('unit-test')) {
            $this->callSilent('ds:unit-test', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module') ]);
        }

        if ($all || $this->option('route-test')) {
            $this->callSilent('ds:route-test', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module'), '--namePtBr' => $namePtBr ]);
        }

        if ($all || $this->option('route-docs')) {
            $this->callSilent('ds:route-docs', [ 'module' => $this->argument('module'), 'name' => $this->argument('name') ?? $this->argument('module'), '--namePtBr' => $namePtBr ]);
        }


        return 0;
    }
}
