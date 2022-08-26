<?php

namespace Devesharp\APIDocs;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    static string $file = '';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!defined('API_DOCS_INIT')) {
            define('API_DOCS_INIT', true);
            $path = realpath(__DIR__ . '/../../config/config.php');
            $this->publishes([$path => config_path('devesharp.php')], 'config');
            $this->mergeConfigFrom($path, 'devesharp');

            $apiDocs = \Devesharp\APIDocs\Generator::getInstance();
            $apiDocs->setTitle(config('devesharp.APIDocs.name', 'API Docs'));
            $apiDocs->setDescription(config('devesharp.APIDocs.description', ''));
            $apiDocs->setVersion(config('devesharp.APIDocs.version', '1.0.0'));

            Provider::$file = config('devesharp.APIDocs.save_file');
            file_put_contents(Provider::$file, '');

            foreach (config('devesharp.APIDocs.refs', []) as $ref) {
                $apiDocs->addRef($ref);
            }

            foreach (config('devesharp.APIDocs.servers', []) as $item) {
                if (!empty($item['url']))
                    $apiDocs->addServers($item['url'], $item['description'] ?? '');
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function __destruct()
    {
        if (defined('API_DOCS_ENABLED')) {
            $apiDocs = \Devesharp\APIDocs\Generator::getInstance();
            file_put_contents(Provider::$file, $apiDocs->toYml());
        }
    }
}
