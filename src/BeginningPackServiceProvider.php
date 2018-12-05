<?php

namespace Dirim\BeginningPackage;

use Illuminate\Support\ServiceProvider;
use Dirim\BeginningPackage\Observers\QueryLoggingObserver;
use Dirim\BeginningPackage\Command\ConvertLangsToVueTranslateJS;
use Dirim\BeginningPackage\Commands\ControllerCrud\CreateControllerCrud;

class BeginningPackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $fromBeginPackConfig = __DIR__.'/config/beginningPack.php';

        $this->publishes([
            $fromBeginPackConfig => config_path('beginningPack.php'),
        ], 'beginningPack');

        if (config('beginningPack.transDevMode')) {
            $this->commands([
                ConvertLangsToVueTranslateJS::class,
            ]);
        }

        $this->queryLogObserversInclude();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/channels.php',
            'logging.channels'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateControllerCrud::class,
            ]);
        }
    }

    private function queryLogObserversInclude()
    {
        $observerPath = config('beginningPack.observerPath');
        $observerPath = $observerPath
                            ? base_path($observerPath)
                            : app_path('Modelss');

        if (is_dir($observerPath)) {
            $modelClassNames = $this->listModelClassNamesInFolder(
                $observerPath
            );

            foreach ($modelClassNames as $name) {
                $name::observe(QueryLoggingObserver::class);
            }
        }

        \App\User::observe(QueryLoggingObserver::class);
    }

    protected function listModelClassNamesInFolder($dir)
    {
        $modelClassNames = [];
        $folderFileNames = array_diff(scandir($dir), ['.', '..']);

        foreach ($folderFileNames as $name) {
            if (is_dir($dir.'/'.$name)) {
                $modelClassNames = array_merge(
                    $modelClassNames,
                    $this->listModelClassNamesInFolder($dir.'/'.$name)
                );
            } else {
                $modelDir = str_replace('/', '\\', stristr($dir, 'Models'));
                $modelClassNames[] = "App\\{$modelDir}\\".str_replace('.php', '', $name);
            }
        }

        return $modelClassNames;
    }
}
