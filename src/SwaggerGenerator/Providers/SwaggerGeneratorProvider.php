<?php

namespace Devesharp\SwaggerGenerator\Providers;

use Illuminate\Support\ServiceProvider;

class SwaggerGeneratorProvider extends ServiceProvider
{
    static string $file = '';
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // check env local
        if (!defined('API_DOCS_INIT')) {
            define('API_DOCS_INIT', true);

            $apiDocs = \Devesharp\SwaggerGenerator\Generator::getInstance();
            $apiDocs->setTitle(config('devesharp_dev_kit.APIDocs.name', 'API Docs'));
            $apiDocs->setDescription(config('devesharp_dev_kit.APIDocs.description', ''));
            $apiDocs->setVersion(config('devesharp_dev_kit.APIDocs.version', '1.0.0'));

            SwaggerGeneratorProvider::$file = config('devesharp_dev_kit.APIDocs.save_file');
            file_put_contents(SwaggerGeneratorProvider::$file, '');

            foreach (config('devesharp_dev_kit.APIDocs.refs', []) as $ref) {
                $apiDocs->addRef($ref);
            }

            foreach (config('devesharp_dev_kit.APIDocs.servers', []) as $item) {
                if (!empty($item['url']))
                    $apiDocs->addServers($item['url'], $item['description'] ?? '');
            }

            if (!empty(config('devesharp_dev_kit.APIDocs.x-logo'))) {
                $apiDocs->setLogo(config('devesharp_dev_kit.APIDocs.x-logo'));
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
            $apiDocs = \Devesharp\SwaggerGenerator\Generator::getInstance();
            file_put_contents(SwaggerGeneratorProvider::$file, $apiDocs->toYml());
        }
    }
}
