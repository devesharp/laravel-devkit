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
            ['name', InputArgument::REQUIRED, 'The name of the service.']
        ];
    }

    protected function getOptions()
    {
        return [
            ['dictionary', 'd', \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create only dictionary'],
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
        $all = true;
        if ($this->option('dictionary') ||
            $this->option('service') ||
            $this->option('policy') ||
            $this->option('validator') ||
            $this->option('transformer') ||
            $this->option('repository') ||
            $this->option('controller') ||
            $this->option('presenter') ||
            $this->option('unit-test') ||
            $this->option('route-test')) {
            $all = false;
        }


        if ($all || $this->option('dictionary')) {
            $this->callSilent('ds:dictionary', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('route')) {
            $this->callSilent('ds:route', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('service')) {
            $this->callSilent('ds:service', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('presenter')) {
            $this->callSilent('ds:presenter', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('validator')) {
            $this->callSilent('ds:validator', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('transformer')) {
            $this->callSilent('ds:transformer', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('policy')) {
            $this->callSilent('ds:policy', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('repository')) {
            $this->callSilent('ds:repository', [ 'name' => $this->argument('name'), '--all' => true ]);
        }

        if ($all || $this->option('controller')) {
            $this->callSilent('ds:controller', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('unit-test')) {
            $this->callSilent('ds:unit-test', [ 'name' => $this->argument('name') ]);
        }

        if ($all || $this->option('route-test')) {
            $this->callSilent('ds:route-test', [ 'name' => $this->argument('name') ]);
        }

        return 0;
    }
}
