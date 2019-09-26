<?php

namespace Dirim\BeginningPackage\Commands;

use Illuminate\Console\Command;

class ConvertLangsToVueTranslateJS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =  'make:vue-translate-js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '
        Convert langs files to vue translate js file
    ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->converToVueTranslateJS();
    }

    protected function converToVueTranslateJS()
    {
        $fromPath = config('beginningPack.fromLangPath');
        $fromPath = $fromPath ? base_path($fromPath) : resource_path('lang');
        
        $toPath = config('beginningPack.toLangPath');
        $toPath = $toPath ? base_path($toPath) : resource_path('lang');
        $toPath .= '/translate.js';

        $langDirs = scandir($fromPath);
        $langs = array_diff($langDirs, ['.', '..', 'translate.js']);

        $transArr = [];
        foreach ($langs as $lang) {
            $globPath = $fromPath . '/'. $lang . '/*.php';

            $files   = glob($globPath);
            $strings = [];

            foreach ($files as $file) {
                $name           = basename($file, '.php');
                $strings[$name] = require $file;
            }

            $transArr[$lang] = $strings;
        }

        $transContent = preg_replace(
            '/:(\w+)/',
            '{${1}}',
            json_encode($transArr)
        );

        $transContent = 'module.exports = '.$transContent;

        $translateJS = fopen($toPath, "w");
        fwrite($translateJS, $transContent);
        fclose($translateJS);

        $this->info('Add vue translate file successfully');
    }
}
