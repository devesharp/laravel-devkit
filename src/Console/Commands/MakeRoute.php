<?php

namespace Devesharp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'ds:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a crud routes';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'RoutesCrud';

    private $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');

        $study = Str::studly($name).'Controller';
        $slug = Str::slug(Str::snake($name));

        $newRoute = $this->files->get(__DIR__ . '/Stubs/routes.stub');
        $newRoute = str_replace('|SLUG|', $slug, $newRoute);
        $newRoute = str_replace('|CONTROLLER_NAME|', $study, $newRoute);
        $web = $this->files->get('routes/api.php');
        if (strpos($web, $newRoute) == false) {
            $this->files->append(
                base_path('routes/api.php'),
                $newRoute
            );
            $this->info('Route written to the bottom of api.php');

            return true;
        } else {
            $this->error('Route already exists. No changes were made.');

            return false;
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the routes'],
        ];
    }
}
